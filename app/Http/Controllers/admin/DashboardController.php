<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\CashAdvance;
use App\Models\Category;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if (can_access(['admin.dashboard'])) {
            return redirect()->route('admin.dashboard');
        } else if (can_access(['user.dashboard'])) {
            return redirect()->route('user.dashboard');
        } else {
            return $request->next;
        }
    }

    public function accessDenied()
    {
        return view('admin.layouts.app')->with('403', 'You do not have access to this page.');
    }

    public function dashboard()
    {
        $totalEmployees = User::whereHas('category', function ($q) {
            $q->where('is_paid', 1);
        })->count();
        $totalActiveToday = Attendance::where([
            ['date', now()->format('Y-m-d')],
            ['status', 'present']
        ])->count();
        $totalAbsentToday = $totalEmployees - $totalActiveToday;
        $pendingCashAdvances = CashAdvance::where('status', 'pending')->count();

        // multiple bar chart
        $lastDate = Attendance::orderBy('date', 'desc')->value('date') ?? now()->format('Y-m-d');
        // Ambil 12 tanggal ke belakang (mundur)
        $dates = collect(range(0, 11))->map(function ($i) use ($lastDate) {
            return Carbon::parse($lastDate)->subDays(11 - $i)->format('Y-m-d');
        });
        // Siapkan data
        $labels = [];
        $presents = [];
        $absents = [];
        $notSets = [];
        foreach ($dates as $date) {
            $present = Attendance::where('date', $date)->where('status', 'present')->count();
            $absent = Attendance::where('date', $date)->where('status', 'absent')->count();
            $notSet = $present != null || $absent != null ? $totalEmployees - ($present + $absent) : 0;
            $labels[] = Carbon::parse($date)->format('d M');
            $presents[] = $present;
            $absents[] = $absent;
            $notSets[] = $notSet;
        }

        // latest salary
        $data = Payroll::orderBy('created_at', 'desc')->limit(4)->get()->reverse()->values();

        $labels1 = [];
        $salary1 = [];
        $totals1 = 0;
        $labels1[] = 'Salary';
        $salary1[] = 0;
        foreach ($data as $payroll) {
            $label = Carbon::parse($payroll->start_date)->format('d M Y') . ' - ' .
                Carbon::parse($payroll->end_date)->format('d M Y');
            $labels1[] = $label;

            $sum = DB::table('payroll_details')
                ->where('id_payroll', $payroll->id)
                ->sum('net_salary');

            $salary1[] = (float) $sum;
            $totals1 += $sum;
        }
        $labels1[] = 'Salary';
        $salary1[] = 0;


        // top user
        $topUsers = User::select(
                'users.*',
                DB::raw('SUM(attendances.working_hour + attendances.overtime) as total_hours')
            )
            ->join('attendances', 'users.id', '=', 'attendances.id_user')
            ->where('attendances.status', 'present')
            ->where('attendances.date', '>=', Carbon::now()->subDays(30))
            ->groupBy('users.id')
            ->orderByDesc('total_hours')
            ->limit(5)
            ->get();


        // job category
        $categories = Category::withCount(['users' => function ($query) {
            $query->where([
                ['status', 'active']
            ]);
        }])->where('is_paid', 1)->get();

        return view('admin.dashboard', compact('totalEmployees', 'totalActiveToday', 'totalAbsentToday', 'pendingCashAdvances', 'labels', 'presents', 'absents', 'notSets', 'labels1', 'salary1', 'totals1', 'topUsers', 'categories'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function printSlip($id)
    {
        $user = User::findOrFail($id);
        $data = ['nama' => 'Sasmita'];
        $pdf = Pdf::loadView('slips', $data);
        return $pdf->download('pay-slips.pdf');
    }
}
