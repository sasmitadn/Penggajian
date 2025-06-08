<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\CashAdvance;
use App\Models\CashAdvanceDetail;
use App\Models\Payroll;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

class AnalyticsController extends Controller
{
    public function attendance(Request $request)
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

        $attendances = Attendance::whereBetween('date', [$start_date, $end_date])
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

        // pie chart absensi
        $labels = ['Hadir', 'Tidak Hadir', 'Izin', 'Belum Absensi'];
        $colors = ["#1d7af3", "#f3545d", "#ffa500", "#6861ce"];
        $values = [
            $totalPresent,
            $totalAbsent,
            $totalPermit,
            $diffInDays - count($attendances) - ($totalPresent + $totalAbsent)
        ];

        // main chart
        $attendances1 = Attendance::whereBetween('date', [$start_date, $end_date])
            ->get()
            ->groupBy('date');
        $labels2 = [];
        $data2 = [];
        $total = User::where('status', 'active')
            ->whereHas('category', fn($q) => $q->where('is_paid', 1))
            ->count();

        $labels2[] = '';
        $data2['present'][] = 0;
        $data2['absent'][] = 0;
        $data2['late'][] = 0;
        $data2['permit'][] = 0;
        $data2['not_set'][] = 0;
        foreach ($attendances1 as $date => $items) {
            $labels2[] = parseDate($date);
            $present = $items->where('status', 'present')->count();
            $absent = $items->where('status', 'absent')->count();
            $late = $items->where('status', 'late')->count();
            $permit = $items->where('status', 'permit')->count();

            $data2['present'][] = $present;
            $data2['absent'][] = $absent;
            $data2['late'][] = $late;
            $data2['permit'][] = $permit;
            $data2['not_set'][] = $total - ($present + $absent + $late + $permit);
        }
        $labels2[] = '';
        $data2['present'][] = 0;
        $data2['absent'][] = 0;
        $data2['late'][] = 0;
        $data2['permit'][] = 0;
        $data2['not_set'][] = 0;
        return view('admin.analytics.attendance', compact(
            'totalPresent',
            'totalAbsent',
            'totalOvertime',
            'totalLate',
            'totalPermit',
            'labels',
            'colors',
            'values',
            'attendances',
            'start_date',
            'end_date',
            'request',
            'labels2',
            'data2'
        ));
    }

    public function salary(Request $request)
    {
        $start_date = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $end_date = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');

        $payrolls = Payroll::whereBetween('start_date', [$start_date, $end_date])->with('detail')->get();
        $labels = [];
        $data = [];

        $labels[] = '';
        $data['salary'][] = 0;
        $data['deduction'][] = 0;
        foreach ($payrolls as $item) {
            // main chart
            $labels[] = parseDate($item->start_date) . '-' . parseDate($item->end_date);
            $data['salary'][] = $item->detail?->sum('net_salary') ?: 0;
            $data['deduction'][] = $item->detail?->sum('amount_deductions') ?: 0;
        }
        $labels[] = '';
        $data['salary'][] = 0;
        $data['deduction'][] = 0;


        // pie chart: lunas vs belum
        $cashAdvance = CashAdvance::whereBetween('date', [$start_date, $end_date])->with('details')->get();
        $unpaid = 0;
        $paid = 0;
        $pending = 0;
        $rejected = 0;
        $approved = 0;
        foreach ($cashAdvance as $item) {
            if ($item->status == 'paid' && $item->is_credit == 0) {
                $paid++;
                continue;
            }
            $done = collect($item->details)
                ->every(fn($d) => $d->payment_method == 'manual' || $d->id_payroll !== null);
            $done ? $paid++ : $unpaid++;
            if ($item->status == 'approved') $approved++;
            if ($item->status == 'rejected') $rejected++;
            if ($item->status == 'pending') $pending++;
        }
        $labels2 = ['Lunas', 'Belum Lunas'];
        $data2 = [$paid, $unpaid];
        $colors = ["#4e73df", "#e74a3b"];
        $labels3 = ['Approved', 'Pending', 'Ditolak'];
        $data3 = [$approved, $pending, $rejected];
        $colors3 = ["#4e73df", '#f6c23e', "#e74a3b"];

        return view('admin.analytics.salary', compact(
            'labels',
            'data',
            'labels2',
            'data2',
            'colors',
            'labels3',
            'data3',
            'colors3',
            'request',
            'start_date',
            'end_date',
            'unpaid','paid','approved','pending','rejected'
        ));
    }
}
