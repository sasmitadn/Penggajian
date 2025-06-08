<?php

use App\Http\Controllers\admin\BookController;
use App\Http\Controllers\user\DashboardController as UserDashboardController;
use App\Http\Controllers\user\CashAdvanceController as UserCashAdvanceController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\LabelController;
use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\AnalyticsController;
use App\Http\Controllers\admin\AttendanceController;
use App\Http\Controllers\admin\PayrollController;
use App\Http\Controllers\admin\CashAdvanceController;
use App\Http\Controllers\admin\ReportController;
use App\Http\Controllers\user\AttendanceController as UserAttendanceController;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [DashboardController::class, 'index'])->name('home')->middleware('auth');
Route::get('/access-denied', [DashboardController::class, 'accessDenied'])->name('access-denied')->middleware('auth');

Route::get('/login', function () {
    return view('admin.layouts.login');
})->name('login');
Route::get('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->middleware('auth')->name('logout');
Route::post('/login-action', [DashboardController::class, 'login'])->name('login.action');

Route::middleware(['auth','checkUserRouteAccess'])->name('user.')->prefix('user')->group(function () {
    Route::get('/my-dashboard', [UserDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [UserDashboardController::class, 'editProfile'])->name('profile');
    Route::put('/profile/{id}', [UserDashboardController::class, 'updateProfile'])->name('profile.update');
    Route::get('/salary', [UserDashboardController::class, 'salary'])->name('salary');
    Route::get('/salary/receipt/{id}', [PayrollController::class, 'salaryReceipt'])->name('salary.receipt');
    
    Route::get('/my_cash_advances/index', [UserCashAdvanceController::class, 'index'])->name('cash_advances.index');
    Route::get('/my_cash_advances/create', [UserCashAdvanceController::class, 'create'])->name('cash_advances.create');
    Route::post('/my_cash_advances/store', [UserCashAdvanceController::class, 'store'])->name('cash_advances.store');
    Route::get('/my_cash_advances/{id}', [UserCashAdvanceController::class, 'show'])->name('cash_advances.show');
    Route::put('/my_cash_advances/update/{id}', [UserCashAdvanceController::class, 'update'])->name('cash_advances.update');
    Route::delete('/my_cash_advances/delete/{id}', [UserCashAdvanceController::class, 'destroy'])->name('cash_advances.delete');
    Route::get('/my_cash_advance/receipt/{id}', [UserCashAdvanceController::class, 'exportReceipt'])->name('cash_advances.export.receipt');
    
    Route::get('/attendances/index', [UserAttendanceController::class, 'index'])->name('attendances.index');
});

Route::middleware(['auth','checkUserRouteAccess'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('/analytics/attendance', [AnalyticsController::class, 'attendance'])->name('analytics.attendance');
    Route::get('/analytics/salary', [AnalyticsController::class, 'salary'])->name('analytics.salary');


    // User Access Routes
    Route::get('/user-access/index', [AdminController::class, 'index'])->name('users.index');
    Route::get('/user-access/create', [AdminController::class, 'create'])->name('users.create');
    Route::post('/user-access/store', [AdminController::class, 'store'])->name('users.store');
    Route::get('/user-access/edit/{id}', [AdminController::class, 'edit'])->name('users.edit');
    Route::put('/user-access/update/{id}', [AdminController::class, 'update'])->name('users.update');
    Route::delete('/user-access/delete/{id}', [AdminController::class, 'destroy'])->name('users.delete');
    Route::get('/user-access/download/import/example', [AdminController::class, 'exampleImport'])->name('users.import.example');
    Route::post('/user-access/import', [AdminController::class, 'import'])->name('users.import');

    Route::get('/attendances/index', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/attendances/create', [AttendanceController::class, 'create'])->name('attendances.create');
    Route::post('/attendances/store', [AttendanceController::class, 'store'])->name('attendances.store');
    Route::get('/attendances/{id}', [AttendanceController::class, 'edit'])->name('attendances.edit');
    Route::put('/attendances/update/{id}', [AttendanceController::class, 'update'])->name('attendances.update');
    Route::delete('/attendances/delete/{id}', [AttendanceController::class, 'destroy'])->name('attendances.delete');

    Route::get('/user-category/{menu}/index', [LabelController::class, 'index'])->name('user_category.index');
    Route::get('/user-category/{menu}/create', [LabelController::class, 'create'])->name('user_category.create');
    Route::post('/user-category/{menu}/store', [LabelController::class, 'store'])->name('user_category.store');
    Route::get('/user-category/{menu}/{id}', [LabelController::class, 'edit'])->name('user_category.edit');
    Route::put('/user-category/{menu}/update/{id}', [LabelController::class, 'update'])->name('user_category.update');
    Route::delete('/user-category/{menu}/delete/{id}', [LabelController::class, 'destroy'])->name('user_category.delete');

    Route::get('/job-category/{menu}/index', [LabelController::class, 'index'])->name('job_category.index');
    Route::get('/job-category/{menu}/create', [LabelController::class, 'create'])->name('job_category.create');
    Route::post('/job-category/{menu}/store', [LabelController::class, 'store'])->name('job_category.store');
    Route::get('/job-category/{menu}/{id}', [LabelController::class, 'edit'])->name('job_category.edit');
    Route::put('/job-category/{menu}/update/{id}', [LabelController::class, 'update'])->name('job_category.update');
    Route::delete('/job-category/{menu}/delete/{id}', [LabelController::class, 'destroy'])->name('job_category.delete');

    Route::get('/payrolls/index', [PayrollController::class, 'index'])->name('payrolls.index');
    Route::get('/payrolls/create', [PayrollController::class, 'create'])->name('payrolls.create');
    Route::post('/payrolls/store', [PayrollController::class, 'store'])->name('payrolls.store');
    Route::get('/payrolls/{id}', [PayrollController::class, 'edit'])->name('payrolls.detail');
    Route::put('/payrolls/update/{id}', [PayrollController::class, 'update'])->name('payrolls.update');
    Route::delete('/payrolls/delete/{id}', [PayrollController::class, 'destroy'])->name('payrolls.delete');
    Route::get('/payrolls/receipt/{id}', [PayrollController::class, 'salaryReceipt'])->name('payrolls.detail.receipt');

    Route::get('/cash_advances/index', [CashAdvanceController::class, 'index'])->name('cash_advances.index');
    Route::get('/cash_advances/create', [CashAdvanceController::class, 'create'])->name('cash_advances.create');
    Route::post('/cash_advances/store', [CashAdvanceController::class, 'store'])->name('cash_advances.store');
    Route::get('/cash_advances/show/{id}', [CashAdvanceController::class, 'show'])->name('cash_advances.show');
    Route::put('/cash_advances/update/{id}', [CashAdvanceController::class, 'update'])->name('cash_advances.update');
    Route::put('/cash_advances/update-detail/{id}', [CashAdvanceController::class, 'updateDetail'])->name('cash_advances.update.detail');
    Route::delete('/cash_advances/delete/{id}', [CashAdvanceController::class, 'destroy'])->name('cash_advances.delete');
    Route::get('/cash_advance/export', [CashAdvanceController::class, 'export'])->name('cash_advance.export');
    Route::get('/cash_advance/receipt/{id}', [CashAdvanceController::class, 'exportReceipt'])->name('cash_advance.export.receipt');

    Route::get('/report/attendances', [ReportController::class, 'attendance'])->name('reports.attendance');
    Route::get('/report/attendances/detail', [ReportController::class, 'attendanceDetail'])->name('reports.attendance.detail');
    Route::get('/report/attendances/export', [ReportController::class, 'attendanceExport'])->name('reports.attendance.export');
    Route::get('/report/attendances/export/{id_user}', [ReportController::class, 'attendanceDetailExport'])->name('reports.attendance.export.detail');

    Route::get('/report/salary', [ReportController::class, 'salary'])->name('reports.salary');
});

Route::get('/component/form-default/{index}', function ($index) {
    return view('components.form-default', [
        'name' => "inputs[$index][value]",
        'label' => "Value",
        'old' => '',
    ])->render();
});
