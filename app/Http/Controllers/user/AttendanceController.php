<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::query();
        $date = $request->date ?? now();
        $start_date = $request->start_date ?? now()->startOfMonth()->format('Y-m-d');
        $end_date = $request->end_date ?? now()->endOfMonth()->format('Y-m-d');
        $query->where('id_user', Auth::id());
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $data = $query->with('user')->orderBy('date', 'asc')->paginate(Config::get('setting.pagination.per_page', 10));
        return view('user.attendance.index', compact('data','request','start_date','end_date'));
    }
}
