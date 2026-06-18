<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Panel\ProfileController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\CatalogController;
use App\Http\Controllers\Student\MyCoursesController;
use App\Http\Controllers\Student\QuizController;
use App\Http\Controllers\Student\DoubtController;

/*
|--------------------------------------------------------------------------
| Student Panel Routes
|--------------------------------------------------------------------------
| All routes prefixed with /student and named student.*
| Protected by auth middleware
*/

Route::prefix('student')->name('student.')->middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->defaults('panelKey', 'student')->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->defaults('panelKey', 'student')->name('profile.update');

    // Course Catalog (Browse & Filter)
    Route::get('/courses', [CatalogController::class, 'index'])->name('catalog');
    Route::get('/courses/{slug}', [CatalogController::class, 'show'])->name('catalog.show');

    // My Courses (Purchased)
    Route::get('/my-courses', [MyCoursesController::class, 'index'])->name('my-courses');
    Route::get('/my-courses/{enrollment}', [MyCoursesController::class, 'show'])->name('my-courses.show');
    Route::get('/my-courses/{enrollment}/video/{video}', [MyCoursesController::class, 'watchVideo'])->name('my-courses.video');
    Route::get('/my-courses/{enrollment}/note/{note}', [MyCoursesController::class, 'viewNote'])->name('my-courses.note');

    // Quiz / Test Series
    Route::get('/quizzes', [QuizController::class, 'index'])->name('quizzes');
    Route::get('/quiz/{quiz}', [QuizController::class, 'show'])->name('quiz.show');
    Route::get('/quiz/{quiz}/attempt', [QuizController::class, 'attempt'])->name('quiz.attempt');
    Route::post('/quiz/{quiz}/submit', [QuizController::class, 'submit'])->name('quiz.submit');
    Route::get('/quiz/{quiz}/results', [QuizController::class, 'results'])->name('quiz.results');

    // Doubts
    Route::get('/doubts', [DoubtController::class, 'index'])->name('doubts');
    Route::post('/doubts', [DoubtController::class, 'store'])->name('doubts.store');
    Route::get('/doubts/{doubt}', [DoubtController::class, 'show'])->name('doubts.show');
    Route::post('/doubts/{doubt}/reply', [DoubtController::class, 'reply'])->name('doubts.reply');
});
