<?php

use Illuminate\Support\Facades\Route;

use App\Models\Batch;
use App\Models\Category;
use App\Services\CategoryService;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\Student\QuizController;
use App\Http\Controllers\Student\DoubtController;

use App\Http\Controllers\PageController;

use App\Models\Ads\AdPopupGlobal;
use App\Models\HR\PerformanceReview;

Route::get('/', function () {
    // Live batches (active / filling_fast) — shown in Popular Batches section
    $batches = Batch::with('course')
        ->where('is_upcoming', false)
        ->whereNotNull('uuid')
        ->take(6)
        ->get();

    // Upcoming batches — shown in "Coming Soon" section on homepage
    $upcomingBatches = Batch::with('course')
        ->where('is_upcoming', true)
        ->whereNotNull('uuid')
        ->take(3)
        ->get();

    // Global popup ad (managed by Ads Panel)
    $globalPopup = AdPopupGlobal::getInstance();

    // Homepage testimonials from HR performance reviews (dynamic)
    $testimonials = PerformanceReview::with(['employee', 'reviewer'])
        ->whereNotNull('feedback')
        ->whereNotNull('rating')
        ->orderByDesc('review_date')
        ->take(6)
        ->get();

    $testimonialCount = $testimonials->count();
    $testimonialAverageRating = $testimonialCount > 0
        ? round((float) $testimonials->avg('rating'), 1)
        : 0;

    $categories = app(CategoryService::class)->activeCategoriesWithSubcategories();

    return view('welcome', compact(
        'batches',
        'upcomingBatches',
        'globalPopup',
        'testimonials',
        'testimonialCount',
        'testimonialAverageRating',
        'categories'
    ));
});

// Public Static Pages
Route::get('/about',   [PageController::class, 'about'])->name('about');
Route::get('/faq',     [PageController::class, 'faq'])->name('faq');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms',   [PageController::class, 'terms'])->name('terms');
Route::get('/careers',                  [\App\Http\Controllers\CareersController::class, 'index'])->name('careers');
Route::get('/careers/apply/{jobPosting}', [\App\Http\Controllers\CareersController::class, 'apply'])->name('careers.apply');
Route::post('/careers/apply/{jobPosting}', [\App\Http\Controllers\CareersController::class, 'storeApplication'])->name('careers.store');
Route::get('/api/categories', [\App\Http\Controllers\CategoryApiController::class, 'index'])->name('api.categories');
Route::get('/api/categories/{category}/subcategories', [\App\Http\Controllers\CategoryApiController::class, 'subcategories'])->name('api.categories.subcategories');

Route::get('/category/{category}', [PageController::class, 'category'])->name('category.show');
Route::get('/category/{category}/{subcategory}', [PageController::class, 'category'])->name('category.show.subcategory')->scopeBindings();
Route::get('/course/{slug}',   [PageController::class, 'courseDetail'])->name('course.detail');



// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Dashboard Routes
Route::middleware(['auth', \App\Http\Middleware\SingleSessionMiddleware::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Student Course Player
    Route::get('/course/{uuid}', [DashboardController::class, 'showCourse'])->name('course.show');

    // Checkout & Payment (must be logged in to pay)
    Route::get('/checkout/{uuid}', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/{uuid}/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/verify', [CheckoutController::class, 'verify'])->name('checkout.verify');


    // Secure Media Routes (Requires valid signature)
    Route::get('/media/video/{filename}', [MediaController::class, 'streamVideo'])->name('media.video')->middleware('signed');
    Route::get('/media/pdf/{filename}', [MediaController::class, 'downloadPdf'])->name('media.pdf')->middleware('signed')->where('filename', '.*');
});

// Faculty Routes
use App\Http\Controllers\Faculty\AuthController as FacultyAuthController;
use App\Http\Controllers\Faculty\DashboardController as FacultyDashboardController;
use App\Http\Controllers\Faculty\CourseManagerController;

Route::prefix('faculty')->name('faculty.')->group(function () {
    Route::get('/login', [FacultyAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [FacultyAuthController::class, 'login'])->name('login.submit');
    Route::match(['get', 'post'], '/logout', [FacultyAuthController::class, 'logout'])->name('logout');

    Route::middleware(['faculty'])->group(function () {
        Route::get('/dashboard', [FacultyDashboardController::class, 'index'])->name('dashboard');

        // My assigned courses — for both faculty and faculty_head
        Route::get('/my-courses', [FacultyDashboardController::class, 'myCourses'])->name('my-courses');

        // Google OAuth — link/unlink Google account for Meet scheduling
        Route::get('/google/redirect', [App\Http\Controllers\Faculty\GoogleAuthController::class, 'redirect'])->name('google.redirect');
        Route::get('/google/callback', [App\Http\Controllers\Faculty\GoogleAuthController::class, 'callback'])->name('google.callback');
        Route::delete('/google/unlink',  [App\Http\Controllers\Faculty\GoogleAuthController::class, 'unlink'])->name('google.unlink');

        // Meetings — schedule Google Meet sessions
        Route::get('/meetings',  [App\Http\Controllers\Faculty\MeetingController::class, 'index'])->name('meetings.index');
        Route::post('/meetings', [App\Http\Controllers\Faculty\MeetingController::class, 'store'])->name('meetings.store');
        Route::delete('/meetings/{meeting}', [App\Http\Controllers\Faculty\MeetingController::class, 'destroy'])->name('meetings.destroy');
        // AJAX helpers for the Schedule modal dropdowns
        Route::get('/meetings/courses/{course}/batches',  [App\Http\Controllers\Faculty\MeetingController::class, 'batchesForCourse'])->name('meetings.batches');
        Route::get('/meetings/batches/{batch}/students',  [App\Http\Controllers\Faculty\MeetingController::class, 'studentsForBatch'])->name('meetings.students');

        // ==== FACULTY HEAD ROUTES ====
        Route::middleware([\App\Http\Middleware\IsFacultyHead::class])->prefix('head')->name('head.')->group(function () {
            // Master Courses
            Route::get('/courses', [App\Http\Controllers\Faculty\Admin\AdminCourseController::class, 'index'])->name('courses.index');
            Route::post('/courses', [App\Http\Controllers\Faculty\Admin\AdminCourseController::class, 'store'])->name('courses.store');
            Route::get('/courses/{course}/edit', [App\Http\Controllers\Faculty\Admin\AdminCourseController::class, 'edit'])->name('courses.edit');
            Route::put('/courses/{course}', [App\Http\Controllers\Faculty\Admin\AdminCourseController::class, 'update'])->name('courses.update');
            Route::delete('/courses/{course}', [App\Http\Controllers\Faculty\Admin\AdminCourseController::class, 'destroy'])->name('courses.destroy');
            
            // Batches — Global management page
            Route::get('/batches', [App\Http\Controllers\Faculty\Admin\AdminBatchController::class, 'index'])->name('batches.index');
            Route::get('/batches/create', [App\Http\Controllers\Faculty\Admin\AdminBatchController::class, 'create'])->name('batches.create');
            Route::post('/batches', [App\Http\Controllers\Faculty\Admin\AdminBatchController::class, 'globalStore'])->name('batches.globalStore');
            Route::get('/batches/{batch}/edit', [App\Http\Controllers\Faculty\Admin\AdminBatchController::class, 'edit'])->name('batches.edit');
            Route::put('/batches/{batch}', [App\Http\Controllers\Faculty\Admin\AdminBatchController::class, 'globalUpdate'])->name('batches.globalUpdate');
            Route::delete('/batches/{batch}', [App\Http\Controllers\Faculty\Admin\AdminBatchController::class, 'destroy'])->name('batches.destroy');
            // Batches — Per-course (from Course Edit page)
            Route::post('/courses/{course}/batches', [App\Http\Controllers\Faculty\Admin\AdminBatchController::class, 'store'])->name('batches.store');
            Route::put('/courses/{course}/batches/{batch}', [App\Http\Controllers\Faculty\Admin\AdminBatchController::class, 'update'])->name('batches.update');
            
            // Faculty Assignment Management
            Route::get('/faculties', [App\Http\Controllers\Faculty\Admin\AdminFacultyController::class, 'index'])->name('faculties.index');
            Route::get('/faculties/assign', [App\Http\Controllers\Faculty\Admin\AdminFacultyController::class, 'createAssign'])->name('faculties.assign.create');
            Route::post('/faculties/assign', [App\Http\Controllers\Faculty\Admin\AdminFacultyController::class, 'assign'])->name('faculties.assign');
            Route::post('/faculties/unassign', [App\Http\Controllers\Faculty\Admin\AdminFacultyController::class, 'unassign'])->name('faculties.unassign');

            // Students
            Route::get('/students', [App\Http\Controllers\Faculty\Admin\AdminStudentController::class, 'index'])->name('students.index');

            Route::resource('categories', App\Http\Controllers\Faculty\Admin\AdminCategoryController::class);
            Route::post('categories/{category}/subcategories', [App\Http\Controllers\Faculty\Admin\AdminCategoryController::class, 'storeSubcategory'])->name('categories.subcategories.store');
            Route::put('categories/{category}/subcategories/{subcategory}', [App\Http\Controllers\Faculty\Admin\AdminCategoryController::class, 'updateSubcategory'])->name('categories.subcategories.update');
            Route::delete('categories/{category}/subcategories/{subcategory}', [App\Http\Controllers\Faculty\Admin\AdminCategoryController::class, 'destroySubcategory'])->name('categories.subcategories.destroy');
        });

        // ==== REGULAR FACULTY ROUTES ====
        // Course Management
        Route::prefix('course/{course}')->name('courses.')->group(function () {
            Route::get('/curriculum', [CourseManagerController::class, 'curriculum'])->name('curriculum');
            
            // Subjects
            Route::post('/subjects', [CourseManagerController::class, 'storeSubject'])->name('subjects.store');
            Route::delete('/subjects/{subject}', [CourseManagerController::class, 'destroySubject'])->name('subjects.destroy');

            // Chapters
            Route::post('/subjects/{subject}/chapters', [CourseManagerController::class, 'storeChapter'])->name('chapters.store');
            Route::delete('/subjects/{subject}/chapters/{chapter}', [CourseManagerController::class, 'destroyChapter'])->name('chapters.destroy');

            // Units (Topics)
            Route::post('/subjects/{subject}/chapters/{chapter}/units', [CourseManagerController::class, 'storeUnit'])->name('units.store');
            Route::delete('/subjects/{subject}/chapters/{chapter}/units/{unit}', [CourseManagerController::class, 'destroyUnit'])->name('units.destroy');

            // Content Management
            Route::get('/content', [App\Http\Controllers\Faculty\ContentManagerController::class, 'index'])->name('content.index');
            Route::post('/units/{unit}/videos', [App\Http\Controllers\Faculty\ContentManagerController::class, 'storeVideo'])->name('videos.store');
            Route::delete('/videos/{video}', [App\Http\Controllers\Faculty\ContentManagerController::class, 'destroyVideo'])->name('videos.destroy');
            Route::post('/units/{unit}/notes', [App\Http\Controllers\Faculty\ContentManagerController::class, 'storeNote'])->name('notes.store');
            Route::delete('/notes/{note}', [App\Http\Controllers\Faculty\ContentManagerController::class, 'destroyNote'])->name('notes.destroy');

            // Advanced Quiz Builder
            Route::get('/quizzes', [App\Http\Controllers\Faculty\QuizManagerController::class, 'index'])->name('quizzes.index');
            Route::post('/units/{unit}/quizzes', [App\Http\Controllers\Faculty\QuizManagerController::class, 'storeQuiz'])->name('quizzes.store');
            Route::delete('/quizzes/{quiz}', [App\Http\Controllers\Faculty\QuizManagerController::class, 'destroyQuiz'])->name('quizzes.destroy');
            
            Route::get('/quizzes/{quiz}/builder', [App\Http\Controllers\Faculty\QuizManagerController::class, 'builder'])->name('quizzes.builder');
            Route::post('/quizzes/{quiz}/publish', [App\Http\Controllers\Faculty\QuizManagerController::class, 'publishQuiz'])->name('quizzes.publish');
            Route::post('/quizzes/{quiz}/import', [App\Http\Controllers\Faculty\QuizManagerController::class, 'importQuiz'])->name('quizzes.import');
            Route::post('/quizzes/{quiz}/sections', [App\Http\Controllers\Faculty\QuizManagerController::class, 'storeSection'])->name('quizzes.sections.store');
            Route::delete('/sections/{section}', [App\Http\Controllers\Faculty\QuizManagerController::class, 'destroySection'])->name('quizzes.sections.destroy');
            
            Route::post('/sections/{section}/questions', [App\Http\Controllers\Faculty\QuizManagerController::class, 'storeQuestion'])->name('quizzes.questions.store');
            Route::delete('/questions/{question}', [App\Http\Controllers\Faculty\QuizManagerController::class, 'destroyQuestion'])->name('quizzes.questions.destroy');

            // Doubts Engine
            Route::get('/doubts', [App\Http\Controllers\Faculty\DoubtManagerController::class, 'index'])->name('doubts.index');
            Route::post('/doubts/{doubt}/reply', [App\Http\Controllers\Faculty\DoubtManagerController::class, 'reply'])->name('doubts.reply');

            // Student Roster
            Route::get('/students', [App\Http\Controllers\Faculty\StudentRosterController::class, 'index'])->name('students.index');

            // Quiz Results & Remarks
            Route::get('/results', [App\Http\Controllers\Faculty\QuizResultsController::class, 'index'])->name('results.index');
            Route::post('/results/{attempt}/remarks', [App\Http\Controllers\Faculty\QuizResultsController::class, 'updateRemarks'])->name('results.remarks.update');
        });
    });
});

// =============================================
// ADS MANAGEMENT PANEL
// =============================================
use App\Http\Controllers\Ads\AuthController as AdsAuthController;
use App\Http\Controllers\Ads\DashboardController as AdsDashboardController;
use App\Http\Controllers\Ads\CampaignController as AdsCampaignController;
use App\Http\Controllers\Ads\PopupController as AdsPopupController;
use App\Http\Controllers\Ads\LeadController as AdsLeadController;
use App\Http\Controllers\Ads\PublicCampaignController;

Route::prefix('ads')->name('ads.')->group(function () {
    // Auth
    Route::get('/login',  [AdsAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdsAuthController::class, 'login'])->name('login.submit');
    Route::match(['get', 'post'], '/logout', [AdsAuthController::class, 'logout'])->name('logout');

    // Protected panel
    Route::middleware(['ads'])->group(function () {
        Route::get('/dashboard', [AdsDashboardController::class, 'index'])->name('dashboard');

        // Campaigns
        Route::get('/campaigns',                    [AdsCampaignController::class, 'index'])->name('campaigns.index');
        Route::get('/campaigns/create',             [AdsCampaignController::class, 'create'])->name('campaigns.create');
        Route::post('/campaigns',                   [AdsCampaignController::class, 'store'])->name('campaigns.store');
        Route::get('/campaigns/{campaign}/edit',    [AdsCampaignController::class, 'edit'])->name('campaigns.edit');
        Route::put('/campaigns/{campaign}',         [AdsCampaignController::class, 'update'])->name('campaigns.update');
        Route::delete('/campaigns/{campaign}',      [AdsCampaignController::class, 'destroy'])->name('campaigns.destroy');
        Route::patch('/campaigns/{campaign}/toggle', [AdsCampaignController::class, 'toggleActive'])->name('campaigns.toggle');

        // Global Popup
        Route::get('/popups',  [AdsPopupController::class, 'index'])->name('popups.index');
        Route::post('/popups', [AdsPopupController::class, 'update'])->name('popups.update');

        // Leads
        Route::get('/leads', [AdsLeadController::class, 'index'])->name('leads.index');
    });
});

// Public Campaign Landing Pages (no auth required)
Route::get('/c/{slug}',        [PublicCampaignController::class, 'show'])->name('campaign.show');
Route::post('/c/{slug}/lead',  [PublicCampaignController::class, 'storeLead'])->name('campaign.lead');

// =============================================
// ADMISSION PANEL
// =============================================
use App\Http\Controllers\Admission\AuthController as AdmissionAuthController;
use App\Http\Controllers\Admission\DashboardController as AdmissionDashboardController;
use App\Http\Controllers\Admission\ContactController as AdmissionContactController;
use App\Http\Controllers\Admission\TrialController as AdmissionTrialController;

Route::prefix('admission')->name('admission.')->group(function () {
    // Auth (public)
    Route::get('/login',   [AdmissionAuthController::class, 'showLogin'])->name('login');
    Route::post('/login',  [AdmissionAuthController::class, 'login'])->name('login.submit');
    Route::match(['get', 'post'], '/logout', [AdmissionAuthController::class, 'logout'])->name('logout');

    // Protected panel
    Route::middleware([\App\Http\Middleware\EnsureAdmissionUser::class])->group(function () {
        Route::get('/dashboard', [AdmissionDashboardController::class, 'index'])->name('dashboard');

        // Contacts (CRM)
        Route::get('/contacts',                          [AdmissionContactController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/{contact}',                [AdmissionContactController::class, 'show'])->name('contacts.show');
        Route::patch('/contacts/{contact}/status',       [AdmissionContactController::class, 'updateStatus'])->name('contacts.status');
        Route::post('/contacts/{contact}/remark',        [AdmissionContactController::class, 'addRemark'])->name('contacts.remark');
        Route::post('/contacts/{contact}/trial',         [AdmissionTrialController::class, 'issue'])->name('contacts.trial.issue');

        // Sync tools
        Route::post('/sync/ad-leads', [AdmissionContactController::class, 'syncAdLeads'])->name('sync.ad-leads');
        Route::post('/sync/users',    [AdmissionContactController::class, 'syncUsers'])->name('sync.users');

        // Trials management
        Route::get('/trials',              [AdmissionTrialController::class, 'index'])->name('trials.index');
        Route::delete('/trials/{trial}',   [AdmissionTrialController::class, 'expire'])->name('trials.expire');
    });
});

// =============================================
// TRIAL STUDENT PORTAL
// =============================================
use App\Http\Controllers\Trial\AuthController as TrialAuthController;
use App\Http\Controllers\Trial\DashboardController as TrialDashboardController;

Route::prefix('trial')->name('trial.')->group(function () {
    // Auth (public)
    Route::get('/login',   [TrialAuthController::class, 'showLogin'])->name('login');
    Route::post('/login',  [TrialAuthController::class, 'login'])->name('login.submit');
    Route::match(['get', 'post'], '/logout', [TrialAuthController::class, 'logout'])->name('logout');

    // Protected trial area
    Route::middleware([\App\Http\Middleware\EnsureTrialStudent::class])->group(function () {
        Route::get('/dashboard', [TrialDashboardController::class, 'index'])->name('dashboard');
        Route::get('/content/chapter/{chapter}', [\App\Http\Controllers\Trial\ContentController::class, 'showChapter'])->name('content.chapter');
    });
});
