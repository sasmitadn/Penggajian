<?php

namespace App\Http\Controllers\admin;

use App\Exports\CashAdvanceExport;
use App\Exports\ExcelExport;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\CashAdvance;
use App\Models\Category;
use App\Models\PayrollDetail;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    public function attendance(Request $request)
    {
        $start_date = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $end_date = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');
        $query = Attendance::query();
        if ($request->filled('id_user')) {
            $query->where('id_user', $request->id_user);
        }
        $data = $query->selectRaw('
                id_user,
                COUNT(*) as total_days,
                SUM(working_hour) as total_working_hour,
                SUM(overtime) as total_overtime,
                SUM(status = "absent") as total_absent,
                ROUND(SUM(working_hour) / COUNT(*), 2) as avg_working_hour,
                ROUND(SUM(overtime) / COUNT(*), 2) as avg_overtime,
                COUNT(CASE WHEN status = "present" THEN 1 END) as total_present,
                COUNT(CASE WHEN status = "late" THEN 1 END) as total_late,
                COUNT(CASE WHEN status = "permit" THEN 1 END) as total_permit
            ')
            ->whereBetween('date', [$start_date, $end_date])
            ->groupBy('id_user')
            ->with('user') // kalau kamu butuh data user juga
            ->paginate(Config::get('setting.pagination.per_page', 10));
        $users = User::where('status', 'active');
        return view('admin.reports.attendance.index', compact('data', 'users', 'request', 'start_date', 'end_date'));
    }

    public function attendanceDetail(Request $request)
    {
        $start_date = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $end_date = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');
        $user = User::findOrFail($request->id_user);
        $data = Attendance::where('id_user', $user->id)
            ->whereBetween('date', [$start_date, $end_date])
            ->with('user')
            ->paginate(Config::get('setting.pagination.per_page', 10));
        return view('admin.reports.attendance.detail', compact('data', 'user', 'request', 'start_date', 'end_date'));
    }

    public function attendanceExport(Request $request)
    {
        $data = [];
        $start_date = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $end_date = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');
        $query = Attendance::query();
        if ($request->filled('id_user')) {
            $query->where('id_user', $request->id_user);
        }
        $attendances = $query->selectRaw('
                id_user,
                COUNT(*) as total_days,
                SUM(working_hour) as total_working_hour,
                SUM(overtime) as total_overtime,
                ROUND(SUM(working_hour) / COUNT(*), 2) as avg_working_hour,
                ROUND(SUM(overtime) / COUNT(*), 2) as avg_overtime,
                COALESCE(SUM(CASE WHEN status = "present" THEN 1 ELSE 0 END), 0) as total_present,
                COALESCE(SUM(CASE WHEN status = "late" THEN 1 ELSE 0 END), 0) as total_late,
                COALESCE(SUM(CASE WHEN status = "permit" THEN 1 ELSE 0 END), 0) as total_permit
            ')
            ->whereBetween('date', [$start_date, $end_date])
            ->groupBy('id_user')
            ->with('user') // kalau kamu butuh data user juga
            ->get();
        $labels = ['Nama', 'Kehadiran', 'Terlambat', 'Izin', 'Total Jam Kerja', 'Total Jam Lembur', 'Avg. Jam Kerja', 'Avg. Jam Lembur'];
        $data = [];
        foreach ($attendances as $item) {
            $data[] = [
                $item->user->name,
                $item->total_present,
                $item->total_late,
                $item->total_permit,
                $item->total_working_hour ?: 0,
                $item->total_overtime ?: 0,
                $item->avg_working_hour ?: 0,
                $item->avg_overtime ?: 0
            ];
        }

        return Excel::download(new ExcelExport($labels, $data), 'laporan-absensi.xlsx');
    }

    public function attendanceDetailExport(Request $request, $id_user)
    {
        $start_date = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $end_date = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');
        $user = User::with('category')->findOrFail($id_user);
        $attendances = Attendance::where('id_user', $user->id)
            ->whereBetween('date', [$start_date, $end_date])
            ->with('user')
            ->get();
        $labels = ['Tanggal','Absensi','Jadwal Masuk','Jadwal Pulang','Masuk','Pulang','Total Jam Kerja','Total Jam Lembur'];
        $data = [];
        foreach ($attendances as $item)
        {
            $status = '';
            if ($item->status === 'present') $status = 'Hadir';
            if ($item->status === 'absent') $status = 'Tidak Hadir';
            if ($item->status === 'late') $status = 'Terlambat';
            $data[] = [
                $item->date,
                $status,
                $user->category->work_start,
                $user->category->work_end,
                $item->start_time,
                $item->end_time,
                $item->working_hour,
                $item->overtime
            ];
        }
        $name = Str::slug($user->name, '_');
        return Excel::download(new ExcelExport($labels, $data), 'absensi_'.$name.'.xlsx');
    }


    /**
     * SALARY
     */
    public function salary(Request $request)
    {
        $start_date = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $end_date = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');
        $users = User::where('status', 'active')->get();
        $query = PayrollDetail::query();
        if ($request->filled('id_user')) {
            $query->where('id_user', $request->id_user);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('start_date', [$request->start_date, $request->end_date]);
        }
        $data = $query->with('user') // asumsi ada relasi ke user
            ->whereBetween('start_date', [$start_date, $end_date])
            ->orderBy(User::select('name')->whereColumn('users.id', 'payroll_details.id_user')) // sort by name
            ->orderBy('start_date')
            ->paginate(Config::get('setting.pagination.per_page', 10));
        foreach ($data as $item) {
            $totals = \App\Models\Attendance::where('id_user', $item->id_user)
                ->whereBetween('date', [$item->start_date, $item->end_date])
                ->selectRaw("
                    SUM(working_hour) as working_hour,
                    SUM(overtime) as overtime,
                    SUM(status = 'present') as total_present,
                    SUM(status = 'late') as total_late,
                    SUM(status = 'absent') as total_absent,
                    SUM(status = 'permit') as total_permit
                ")
                ->first();

            $item->working_hour = $totals->working_hour;
            $item->overtime = $totals->overtime;
            $item->total_present = $totals->total_present ?? 0;
            $item->total_late = $totals->total_late ?? 0;
            $item->total_absent = $totals->total_absent ?? 0;
            $item->total_permit = $totals->total_permit ?? 0;
        }
        return view('admin.reports.salary.index', compact('data', 'users', 'start_date', 'end_date', 'request'));
    }

        public function salaryExport(Request $request)
    {
        $start_date = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $end_date = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');
        $users = User::where('status', 'active')->get();
        $query = PayrollDetail::query();
        if ($request->filled('id_user')) {
            $query->where('id_user', $request->id_user);
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('start_date', [$request->start_date, $request->end_date]);
        }
        $payrollDetails = $query->with('user') // asumsi ada relasi ke user
            ->whereBetween('start_date', [$start_date, $end_date])
            ->orderBy(User::select('name')->whereColumn('users.id', 'payroll_details.id_user')) // sort by name
            ->orderBy('start_date')
            ->get();
        
        $labels = [
            'Tanggal Awal', 'Tanggal Akhir', 'Nama', 'Jabatan', 'Kehadiran', 'Terlambat', 'Izin', 'Tidak Hadir',
            'Total Jam Kerja', 'Total Jam Lembur', 'Jumlah Gaji', 'Jumlah Lembur', 'Pengurangan Pinjaman', 'Total Gaji'
        ];
        $data = [];
        foreach ($payrollDetails as $item) {
            $totals = \App\Models\Attendance::where('id_user', $item->id_user)
                ->whereBetween('date', [$item->start_date, $item->end_date])
                ->selectRaw("
                    SUM(working_hour) as working_hour,
                    SUM(overtime) as overtime,
                    SUM(status = 'present') as total_present,
                    SUM(status = 'late') as total_late,
                    SUM(status = 'absent') as total_absent,
                    SUM(status = 'permit') as total_permit
                ")
                ->first();

            $item->working_hour = $totals->working_hour;
            $item->overtime = $totals->overtime;
            $item->total_present = $totals->total_present ?? 0;
            $item->total_late = $totals->total_late ?? 0;
            $item->total_absent = $totals->total_absent ?? 0;
            $item->total_permit = $totals->total_permit ?? 0;

            $data[] = [
                $item->start_date, $item->end_date, $item->user?->name, $item->user?->category?->name,
                $item->total_present, $item->total_late, $item->total_permit, $item->total_absent,
                $item->working_hour, $item->overtime, $item->amount_salary, $item->amount_overtime, $item->amount_deductions, $item->net_salary
            ];
        }
        return Excel::download(new ExcelExport($labels, $data), 'laporan_gaji.xlsx');
    }
}
