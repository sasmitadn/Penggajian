<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\CashAdvance;
use App\Models\PayrollDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        // check filter
        $start_date = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $end_date = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');
        $start = Carbon::parse($start_date);
        $end = Carbon::parse($end_date);
        $diffInDays = $start->diffInDays($end) + 1;
        $validator = Validator::make($request->all(), [
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }


        $user = User::findOrFail(Auth::id());
        $attendances = Attendance::where('id_user', $user->id)
            ->whereBetween('date', [$start_date, $end_date])
            ->orderBy('date', 'desc')
            ->get();
        $totalPresent = 0;
        $totalAbsent = 0;
        $totalOvertime = 0;
        $totalLate = 0;
        $totalPermit = 0;
        for ($i = 0; $i < count($attendances); $i++) {
            $item = $attendances[$i];
            if ($item->status == 'present') $totalPresent++;
            if ($item->status == 'absent') $totalAbsent++;
            if ($item->status == 'permit') $totalPermit++;
            $totalOvertime += $item->overtime ?? 0;
        }
        $totalCashAdvance = CashAdvance::where('id_user', $user->id)
            ->whereBetween('created_at', [$start_date, $end_date])
            ->count();
        $topUsers = User::select('users.*', DB::raw('COUNT(attendances.id) as total_present'))
            ->join('attendances', 'users.id', '=', 'attendances.id_user')
            ->where('attendances.status', 'present')
            ->where('attendances.date', '>=', Carbon::now()->subDays(30))
            ->groupBy('users.id')
            ->orderByDesc('total_present')
            ->limit(5)
            ->get();
        $topUser = false;
        foreach ($topUsers as $item) {
            if ($item->id == $user->id) {
                $topUser = true;
            } else {
                $topUser = false;
            }
        }

        // pie chart absensi
        $labels = ['Hadir', 'Tidak Hadir', 'Izin', 'Terlambat', 'Belum Absensi'];
        $colors = ["#4e73df", "#e74a3b", "#f6c23e", "#36b9cc", "#858796"];
        $values = [
            $totalPresent,
            $totalAbsent,
            $totalPermit,
            $totalLate,
            $diffInDays - count($attendances) - ($totalPresent + $totalAbsent)
        ];

        // chart salary
        $labels1 = [];
        $salary1 = [];
        $overtime1 = [];
        foreach ($attendances as $item) {
            $priceDaily = $user->category?->price_daily ?? 0;
            $priceOvertime = $user->category?->price_overtime ?? 0;
            if ($item->status == 'present') {
                $item->salary = $priceDaily;
                $item->totalPriceOvertime = $item->overtime * $priceOvertime;
            } elseif ($item->status == 'absent') {
                $item->salary = 0;
                $item->totalPriceOvertime = 0;
            } else {
                $item->salary = null;
                $item->totalPriceOvertime = null;
            }
            $labels1[] = Carbon::parse($item->date)->format('d M Y');
            $salary1[] = $item->salary ?? 0;
            $overtime1[] = $item->totalPriceOvertime ?? 0;
        }

        // chart 2
        $labels2 = [];
        $values2 = [];
        $payrollDetails = PayrollDetail::where('id_user', Auth::id())->latest()->limit(12)->get()->reverse();
        foreach ($payrollDetails as $item) {
            $labels2[] = parseDate($item->start_date) . ' - ' . parseDate($item->end_date);
            $values2[] = $item->net_salary;
        }
        while (count($labels2) < 12) {
            $labels2[] = '-';
            $values2[] = 0;
        }

        $attendances1 = Attendance::where('id_user', $user->id)
            ->whereBetween('date', [$start_date, $end_date])
            ->orderBy('date', 'desc')
            ->paginate(Config::get('setting.pagination.per_page', 10));
        return view('user.my_dashboard', compact(
            'totalPresent',
            'totalAbsent',
            'totalOvertime',
            'totalLate',
            'totalPermit',
            'totalCashAdvance',
            'topUser',
            'labels',
            'colors',
            'values',
            'attendances',
            'attendances1',
            'user',
            'start_date',
            'end_date',
            'labels1',
            'salary1',
            'overtime1',
            'labels2',
            'values2',
            'request'
        ));
    }

    public function editProfile()
    {
        $user = Auth::user();
        $data = User::findOrFail($user->id);
        return view('user.profile', compact('data'));
    }

    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:15|unique:users,phone,' . $id
        ]);
        $user = Auth::user();
        $model = User::findOrFail($user->id);
        $model->name = $request->name;
        $model->email = $request->email;
        if (filled($request->password)) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            $model->password = Hash::make($request->password);
        }
        $model->address = $request->address;
        $model->phone = $request->phone;
        $model->save();
        return back()->with('success', 'Profile updated successfully.');
    }

    public function salary()
    {
        $data = PayrollDetail::with('user')->where('id_user', Auth::id())->get();
        return view('user.salary.index', compact('data'));
    }

    public function salaryReceipt(Request $request, $id)
    {
        $data = PayrollDetail::with(['user', 'user.category'])->findOrFail($id);
        $data->subtotal = $data->amount_salary + $data->amount_overtime;
        $signature = $data->user->name . ';' . $data->id;
        $data->hash = substr(hash('sha256', $signature), -10);
        return view('export.salary', compact('data'));
    }
}
