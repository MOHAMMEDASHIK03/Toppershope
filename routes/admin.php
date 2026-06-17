<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ForgotPasswordController;

// Public Admin Auth Routes
Route::middleware('guest:admin')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showEmailForm'])->name('password.forgot');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->middleware('throttle:5,1')->name('password.forgot.send');
    Route::get('/forgot-password/verify', [ForgotPasswordController::class, 'showVerifyForm'])->name('password.forgot.verify');
    Route::post('/forgot-password/verify', [ForgotPasswordController::class, 'verifyOtp'])->middleware('throttle:10,1')->name('password.forgot.verify.submit');
    Route::post('/forgot-password/resend', [ForgotPasswordController::class, 'resendOtp'])->middleware('throttle:3,1')->name('password.forgot.resend');
    Route::get('/forgot-password/reset', [ForgotPasswordController::class, 'showResetForm'])->name('password.forgot.reset');
    Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'resetPassword'])->middleware('throttle:5,1')->name('password.forgot.reset.submit');
});

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Admin Routes
Route::middleware(\App\Http\Middleware\EnsureAdminUser::class)->group(function () {
    // Dashboard & Revenue
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/expenses', [DashboardController::class, 'storeExpense'])->name('expenses.store');
    Route::get('/impersonate/{panel}', [DashboardController::class, 'impersonate'])->name('impersonate');

    // Ecosystem Audit Logs
    Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-logs.index');

    // Ecosystem Branches
    Route::resource('branches', \App\Http\Controllers\Admin\BranchController::class);

    // HR Users Management (Full CRUD)
    Route::resource('hr-users', \App\Http\Controllers\Admin\HrUserController::class);
    
    // Global Staff View (Ads, Admission, Faculty)
    Route::get('/staff-registry', [\App\Http\Controllers\Admin\StaffController::class, 'index'])->name('staff.index');

    // Category management
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::post('categories/{category}/subcategories', [\App\Http\Controllers\Admin\CategoryController::class, 'storeSubcategory'])->name('categories.subcategories.store');
    Route::put('categories/{category}/subcategories/{subcategory}', [\App\Http\Controllers\Admin\CategoryController::class, 'updateSubcategory'])->name('categories.subcategories.update');
    Route::delete('categories/{category}/subcategories/{subcategory}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroySubcategory'])->name('categories.subcategories.destroy');

    // Global Academic View (Courses, Batches)
    Route::get('/academic-overview', [\App\Http\Controllers\Admin\GlobalAcademicController::class, 'index'])->name('academic.index');
    
    // Natively Create/Edit Courses & Batches
    Route::resource('courses', \App\Http\Controllers\Admin\CourseController::class)->except(['index', 'show']);
    Route::post('courses/{course}/batches', [\App\Http\Controllers\Admin\BatchController::class, 'store'])->name('courses.batches.store');
    Route::put('courses/{course}/batches/{batch}', [\App\Http\Controllers\Admin\BatchController::class, 'update'])->name('courses.batches.update');

    // Access Control
    Route::get('/access-control/password-reset', [\App\Http\Controllers\Admin\AccessControlController::class, 'passwordResetIndex'])->name('access-control.password-reset');
    Route::post('/access-control/force-reset', [\App\Http\Controllers\Admin\AccessControlController::class, 'forcePasswordReset'])->name('access-control.force-reset');
    Route::get('/access-control/sessions', [\App\Http\Controllers\Admin\AccessControlController::class, 'sessionsIndex'])->name('access-control.sessions');
    Route::post('/access-control/sessions/kill', [\App\Http\Controllers\Admin\AccessControlController::class, 'killSession'])->name('access-control.kill-session');

    // Quiz Management
    Route::prefix('courses/{course}')->name('courses.')->group(function () {
        Route::get('/quizzes', [\App\Http\Controllers\Admin\QuizManagerController::class, 'index'])->name('quizzes.index');
        Route::post('/units/{unit}/quizzes', [\App\Http\Controllers\Admin\QuizManagerController::class, 'storeQuiz'])->name('quizzes.store');
        Route::delete('/quizzes/{quiz}', [\App\Http\Controllers\Admin\QuizManagerController::class, 'destroyQuiz'])->name('quizzes.destroy');
        Route::get('/quizzes/{quiz}/builder', [\App\Http\Controllers\Admin\QuizManagerController::class, 'builder'])->name('quizzes.builder');
        Route::post('/quizzes/{quiz}/publish', [\App\Http\Controllers\Admin\QuizManagerController::class, 'publishQuiz'])->name('quizzes.publish');
        Route::post('/quizzes/{quiz}/import', [\App\Http\Controllers\Admin\QuizManagerController::class, 'importQuiz'])->name('quizzes.import');
        Route::post('/quizzes/{quiz}/sections', [\App\Http\Controllers\Admin\QuizManagerController::class, 'storeSection'])->name('quizzes.sections.store');
        Route::delete('/sections/{section}', [\App\Http\Controllers\Admin\QuizManagerController::class, 'destroySection'])->name('quizzes.sections.destroy');
        Route::post('/sections/{section}/questions', [\App\Http\Controllers\Admin\QuizManagerController::class, 'storeQuestion'])->name('quizzes.questions.store');
        Route::delete('/questions/{question}', [\App\Http\Controllers\Admin\QuizManagerController::class, 'destroyQuestion'])->name('quizzes.questions.destroy');
    });
});
