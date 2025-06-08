<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('status')) {
            if ($request->status == 'not_set') {
                $query->whereDoesntHave('attendances', function ($query) use ($request) {
                    $query->whereDate('date', $request->date ?? now()->format('Y-m-d'));
                });
            } else {
                $query->whereHas('attendances', function ($query) use ($request) {
                    $query->whereDate('date', $request->date ?? now()->format('Y-m-d'))
                        ->where('status', $request->status);
                });
            }
        }
        if ($request->filled('id_category')) {
            $query->where('id_category', $request->id_category);
        }
        $data = $query->whereHas('category', function ($query) {
            $query->where('is_paid', 1);
        })->with('category')->orderBy('name', 'asc')->paginate(Config::get('setting.pagination.per_page', 10));
        $date = $request->date ?? now();
        $date = Carbon::parse($date)->format('Y-m-d');
        $attendances = Attendance::where('date', $date)->with(['user', 'user.category'])->get();
        $categories = Category::where('status', 'active')->get();
        return view('admin.attendance.index', compact('data', 'attendances', 'request', 'date', 'categories'));
    }

    public function store(Request $request)
    {
        $date = $request->date ? $request->date : now()->format('Y-m-d');
        $user = User::with('category')->find($request->id_user);
        $workStart = Carbon::parse($user->category->work_start);
        $workEnd = Carbon::parse($user->category->work_end);
        $startTime = Carbon::parse($request->start_time);
        $minutes = $startTime->diffInMinutes($workEnd, false);
        $working_hour = $minutes > 0 ? $minutes / 60 : 0;

        $data = Attendance::firstOrNew([
            'id_user' => $request->id_user,
            'date' => $date,
        ]);
        $data->id_user = $request->id_user;
        $data->date = $date;
        if ($startTime > $workStart && $request->status == 'present') {
            $data->status = 'late';
        } else {
            $data->status = $request->status;
        }
        $data->start_time = $request->start_time;
        if ($data->status == 'absent' || $data->status == 'permit') $working_hour = 0;
        $data->working_hour = $working_hour;
        $data->save();
        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $data = Attendance::findOrFail($id);

        if ($data) {
            $user = User::with('category')->where('id', $data->id_user)->first();
            $data->end_time = $request->end_time;
            $start = Carbon::parse($user->category->work_end);
            $end = Carbon::parse($request->end_time);
            $minutes = $start->diffInMinutes($end, false); // hasil bisa negatif kalau end < start
            $overtime = $minutes > 0 ? $minutes / 60 : 0;
            $data->overtime = $overtime;
            $data->save();
            return response()->json(['success' => true]);
        }

        return response()->json(['error' => 'Not found'], 404);
    }
}
