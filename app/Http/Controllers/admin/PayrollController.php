<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Book;
use App\Models\CashAdvance;
use App\Models\CashAdvanceDetail;
use App\Models\Payroll;
use App\Models\PayrollDetail;
use App\Models\Student;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PayrollController extends Controller
{
    public function index(Request $request)
    {
        $query = Payroll::query();
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('start_date', '>=', $request->start_date)
                ->whereDate('start_date', '<=', $request->end_date);
        }
        $data = $query->with('detail')->orderBy('created_at', 'desc')->paginate(Config::get('setting.pagination.per_page', 10));

        // $data1 = DB::table('payrolls')
        //     ->select(
        //         'start_date',
        //         'end_date',
        //         DB::raw('SUM(net_salary) as total_salary'),
        //         'status'
        //     )
        //     ->groupBy('start_date', 'end_date', 'status')
        //     ->get();
        return view('admin.payrolls.index', compact('data', 'request'));
    }

    public function create()
    {
        return view('admin.payrolls.create');
    }

    public function edit(Request $request, $id)
    {
        $query = PayrollDetail::query();
        if ($request->filled('id_user')) {
            $query->where('id_user', $request->id_user);
        }
        $data = $query->where('id_payroll', $id)
            ->with(['user', 'user.category'])
            ->paginate(Config::get('setting.pagination.per_page', Config::get('setting.pagination.per_page', 10)));
        $start_date = Payroll::find($id)->start_date;
        $end_date = Payroll::find($id)->end_date;
        $status = Payroll::find($id)->status;
        $users = User::where('status', 'active')->get();
        return view('admin.payrolls.detail', compact('data', 'id', 'users', 'request', 'start_date', 'end_date', 'status'));
    }
    public function destroy($id)
    {
        Payroll::find($id)->delete();
        return back()->with('success', 'Data berhasil dihapus.');
    }
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        $payroll = new Payroll();
        $payroll->start_date = $request->start_date;
        $payroll->end_date = $request->end_date;
        $payroll->save();
        $users = User::where('status', 'active')->with('category')->get();
        foreach ($users as $user) {
            if ($user->category?->is_paid == 0) continue;
            $attendances = Attendance::where('id_user', $user->id)
                ->whereDate('date', '>=', $request->start_date)
                ->whereDate('date', '<=', $request->end_date)
                ->whereIn('status', ['present', 'late'])
                ->with(['user', 'user.category'])
                ->get();
            $totalDays = $attendances->count();
            $totalOvertime = $attendances->sum('overtime');
            $amountSalary = ($totalDays * $user->category->price_daily);
            $amountOvertime = ($totalOvertime * $user->category->price_overtime);

            $totalDeductions = 0;
            $cashAdvanceDetail = CashAdvanceDetail::where('id_user', $user->id)
                ->where('payment_method', 'auto')
                ->where('id_payroll', null)
                ->orderByDesc('amount')
                ->first();
            $maxAllowance = $amountSalary + $amountOvertime;
            $totalDeductions = 0;
            if ($cashAdvanceDetail != null) {
                $totalDeductions = $cashAdvanceDetail->amount <= $maxAllowance ? $cashAdvanceDetail->amount : 0;
                CashAdvanceDetail::where('id', $cashAdvanceDetail->id)->update([
                    'id_payroll' => $payroll->id
                ]);
            }
            $netSalary = $maxAllowance - $totalDeductions;

            $data = new PayrollDetail();
            $data->id_payroll = $payroll->id;
            $data->id_user = $user->id;
            $data->start_date = $request->start_date;
            $data->end_date = $request->end_date;
            $data->price_daily = $user->category->price_daily;
            $data->price_overtime = $user->category->price_overtime;
            $data->work_start = $user->category->work_start;
            $data->work_end = $user->category->work_end;
            $data->total_days = $totalDays;
            $data->total_overtime = $totalOvertime;
            $data->amount_salary = $amountSalary;
            $data->amount_overtime = $amountOvertime;
            $data->amount_deductions = $totalDeductions;
            $data->net_salary = $netSalary;
            $data->status = 'pending';
            $data->save();
        }
        return redirect()->route('admin.payrolls.index')->with('success', 'Data berhasil dibuat.');
    }
    public function update(Request $request, $id) {}

    public function salaryReceipt(Request $request, $id)
    {
        $data = PayrollDetail::with(['user', 'user.category'])->findOrFail($id);
        $data->subtotal = $data->amount_salary + $data->amount_overtime;
        $signature = $data->user->name . ';' . $data->id;
        $data->hash = substr(hash('sha256', $signature), -10);
        return view('export.salary', compact('data'));
        // $pdf = Pdf::loadView('export.salary', compact('data'));
        // return $pdf->download('gaji-'.$id.'.pdf');
    }
}
