<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HR\AuthController;
use App\Http\Controllers\HR\DashboardController;

// HR Authentication Routes (guest)
Route::middleware('guest:hr')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// HR Protected Routes
Route::middleware(['auth:hr', 'hr'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Organization
    Route::resource('departments', \App\Http\Controllers\HR\DepartmentController::class);
    Route::resource('designations', \App\Http\Controllers\HR\DesignationController::class);
    
    // Personnel
    Route::resource('employees', \App\Http\Controllers\HR\EmployeeController::class);
    Route::patch('employees/{employee}/toggle-status', [\App\Http\Controllers\HR\EmployeeController::class, 'toggleStatus'])->name('employees.toggle-status');
    Route::get('employees/designations/{department}', [\App\Http\Controllers\HR\EmployeeController::class, 'getDesignations'])->name('employees.designations');
    Route::post('employees/sync-panels', [\App\Http\Controllers\HR\EmployeeController::class, 'syncFromPanels'])->name('employees.sync-panels');
    
    // Attendance
    Route::get('attendance', [\App\Http\Controllers\HR\AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('attendance/mark', [\App\Http\Controllers\HR\AttendanceController::class, 'mark'])->name('attendance.mark');
    Route::get('attendance/report/{employee}', [\App\Http\Controllers\HR\AttendanceController::class, 'report'])->name('attendance.report');
    
    // Leave Management
    Route::resource('leave-types', \App\Http\Controllers\HR\LeaveTypeController::class);
    Route::resource('leaves', \App\Http\Controllers\HR\LeaveController::class);
    
    // Payroll Management
    Route::get('payroll/settings', [\App\Http\Controllers\HR\PayrollController::class, 'settings'])->name('payroll.settings');
    Route::post('payroll/settings', [\App\Http\Controllers\HR\PayrollController::class, 'updateSettings'])->name('payroll.settings.update');
    Route::get('payroll/{payroll}/pdf', [\App\Http\Controllers\HR\PayrollController::class, 'pdf'])->name('payroll.pdf');
    Route::resource('payroll', \App\Http\Controllers\HR\PayrollController::class);
    
    // Performance Management
    Route::resource('kpis', \App\Http\Controllers\HR\KpiController::class);
    Route::resource('performance-reviews', \App\Http\Controllers\HR\PerformanceReviewController::class);
    
    // Recruitment Management
    Route::resource('job-postings', \App\Http\Controllers\HR\JobPostingController::class);
    Route::resource('job-applications', \App\Http\Controllers\HR\JobApplicationController::class);
    Route::resource('interviews', \App\Http\Controllers\HR\InterviewController::class);
    
    // Documents & Communications
    Route::resource('employee-documents', \App\Http\Controllers\HR\EmployeeDocumentController::class);
    Route::resource('announcements', \App\Http\Controllers\HR\AnnouncementController::class);

    // Add more HR routes here later...
});
