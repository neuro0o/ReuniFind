<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HandoverRequestController;
use App\Http\Controllers\HandoverMessageController;
use App\Http\Controllers\ItemReportController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\AccountSettingsController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// LANDING
Route::get('/', function () {
    return view('landing');
    // return view('user/dashboard');
});

/*----------------- AUTH ROUTES -------------------*/

// REGISTER
Route::get('register', [RegistrationController::class, 'showForm'])
    ->name('register');
Route::post('register', [RegistrationController::class, 'processForm'])
    ->name('register.process');

// LOGIN
Route::get('login', [AuthController::class, 'showLoginForm'])
    ->name('login');
Route::post('login', [AuthController::class, 'login'])
    ->name('login.process');

// Logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/'); // landing page
})->name('logout');


// Account Settings
Route::middleware(['auth'])->prefix('account')->group(function () {
    Route::get('/settings', [AccountSettingsController::class, 'index'])->name('account.settings');
    Route::put('/update', [AccountSettingsController::class, 'updateProfile'])->name('account.update');
    Route::put('/update-password', [AccountSettingsController::class, 'updatePassword'])->name('account.update.password');
    Route::delete('/account/delete', [AccountSettingsController::class, 'deleteAccount'])->name('account.delete');

    // Route for AJAX modal
    Route::get('/settings/modal', [AccountSettingsController::class, 'modal'])->name('account.settings.modal');
});

// USER HOME @ DASHBOARD
Route::get('/dashboard', function () {
    return view('user.dashboard');
})->middleware('auth')->name('user.dashboard');

// USER HOME @ DASHBOARD STATISTICS
Route::get('/dashboard', [UserDashboardController::class, 'index'])
    ->middleware('auth')
    ->name('user.dashboard');


/*----------------- ADMIN ROUTES -------------------*/
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.admin_dashboard');

    // user managements
    Route::get('/admin/users', [AdminController::class, 'manageUsers'])->name('admin.users.index');

    Route::get('/admin/users/create', [AdminController::class, 'createUser'])->name('admin.users.create');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');

    Route::get('/admin/users/{id}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
    Route::put('/admin/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::put('/admin/users/{id}/reset-profile', [AdminController::class, 'resetProfile'])->name('admin.users.resetProfile');


    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

    // Item Category CRUD
    Route::resource('categories', \App\Http\Controllers\ItemCategoryController::class);

    // Item Location CRUD
    Route::resource('locations', \App\Http\Controllers\ItemLocationController::class);

    // manage lost item reports
    Route::get('/reports/lost', [AdminController::class, 'manageLostReports'])
        ->name('admin.manage_report_lost');

    // manage found item reports
    Route::get('/reports/found', [AdminController::class, 'manageFoundReports'])
        ->name('admin.manage_report_found');

    // View all reports
    Route::get('/reports/all', [AdminController::class, 'viewReports'])
        ->name('admin.manage_report_view');

    // Approve or reject item reports
    Route::post('/reports/{id}/approve', [AdminController::class, 'approveReport'])->name('admin.approve_report');
    Route::post('/reports/{id}/reject', [AdminController::class, 'rejectReport'])->name('admin.reject_report');
    Route::delete('/reports/{id}/delete', [AdminController::class, 'deleteReport'])->name('admin.delete_report');

    // Detailed view
    Route::get('/reports/{id}', [AdminController::class, 'showReport'])
        ->name('admin.report_detail');
});

/*----------------- LOST & FOUND REPORT MODULE ROUTES -------------------*/
Route::middleware(['auth'])->prefix('item_report')->group(function () {
    
    // Report Lost Item
    Route::get('/report_lost', [ItemReportController::class, 'reportLost'])->name('item_report.report_lost');
    Route::post('/report_lost', [ItemReportController::class, 'processForm'])->name('item_report.store');
    
    // Report Found Item
    Route::get('/report_found', [ItemReportController::class, 'reportFound'])->name('item_report.report_found');
    Route::post('/report_found', [ItemReportController::class, 'processForm'])->name('item_report.store');
    
    // View All Reports
    Route::get('/view', [ItemReportController::class, 'viewReports'])
    ->middleware('auth')
    ->name('item_report.view');

    // View User's Reports
    Route::get('/my_report', [ItemReportController::class, 'myReports'])->name('item_report.my_report');

    // Edit Own Report
    Route::get('/edit/{id}', [ItemReportController::class, 'edit'])->name('item_report.edit');
    Route::put('/update/{id}', [ItemReportController::class, 'update'])->name('item_report.update');
    
    // Delete Own Report
    Route::delete('/delete/{id}', [ItemReportController::class, 'destroy'])->name('item_report.destroy');

    // Suggested Matches
    Route::get('/matchmaking', [ItemReportController::class, 'matchmaking'])->name('item_report.suggested_matches');
    Route::post('/item_report/match/dismiss/{id}', [ItemReportController::class, 'dismissMatch'])->name('item_report.dismiss_match');
    Route::post('/item_report/match/undo_dismiss/{id}', [ItemReportController::class, 'undoDismiss'])->name('item_report.undo_dismiss');

    // Map Display
    Route::get('/item_report/map/{id}', [App\Http\Controllers\MapController::class, 'show'])->name('map.show');

    // Instant Handover Request
    Route::post('/{reportID}/handover', [HandoverRequestController::class, 'instantHandover'])
        ->name('handover.instant')
        ->middleware('auth');

    // temp routes
    Route::get('/reports/{id}', [ItemReportController::class, 'show'])->name('reports.show');
    
});

/*----------------- HANDOVER REQUEST + CHAT -------------------*/
Route::middleware(['auth'])->prefix('handover')->group(function () {

    // Show all handovers related to the logged-in user
    Route::get('/', [HandoverRequestController::class, 'index'])
        ->name('handover.index');

    // Fetch opposite-type reports
    Route::get('/opposite-reports/{reportID}', [HandoverRequestController::class, 'getOppositeReports'])
        ->name('handover.opposite_reports');
    
    // Store new handover request
    Route::post('/store', [HandoverRequestController::class, 'store'])
        ->name('handover.store');
        
    Route::post('/instant/{reportID}', [HandoverRequestController::class, 'instantHandover'])
        ->name('handover.instant');

    // Cancel handover request (sender only)
    Route::delete('/{id}/cancel', [HandoverRequestController::class, 'cancel'])
        ->name('handover.cancel');

    // Show details of a handover
    Route::get('/{id}/details', [HandoverRequestController::class, 'show'])
        ->name('handover.show');

    // Update handover status (approve, reject, complete)
    Route::patch('/{id}/update', [HandoverRequestController::class, 'update'])
        ->name('handover.update');

    // Handover Form Routes
    // Route::get('/{id}/generate-form', [HandoverRequestController::class, 'generateHandoverForm'])
    //     ->name('handover.generateForm');
    // Route::post('/{id}/upload-form', [HandoverRequestController::class, 'uploadHandoverForm'])
    //     ->name('handover.uploadForm');
    // Route::get('/{id}/view-form', [HandoverRequestController::class, 'viewHandoverForm'])
    //     ->name('handover.viewForm');

    // Handover Form Routes (must be approved)
    Route::get('/handover/{requestID}/form/download', [HandoverRequestController::class, 'downloadHandoverForm'])
        ->name('handover.form.download')
        ->middleware('auth');

    Route::post('/handover/{requestID}/form/upload', [HandoverRequestController::class, 'uploadHandoverForm'])
        ->name('handover.form.upload')
        ->middleware('auth');

    Route::get('/handover/{requestID}/form/view', [HandoverRequestController::class, 'viewHandoverForm'])
        ->name('handover.form.view')
        ->middleware('auth');


    /* ----- CHAT MESSAGES ----- */
    // Show chat list (all conversations)
    Route::get('/chat', [HandoverMessageController::class, 'index'])
        ->name('handover.chat.index');
    
    // Get chat updates for live refresh (AJAX)
    Route::get('/chat/updates', [HandoverMessageController::class, 'getChatUpdates'])
        ->name('handover.chat.updates');
    
    // Show specific chat conversation
    Route::get('/chat/{requestID}', [HandoverMessageController::class, 'show'])
        ->name('handover.chat.show');
    
    // Send message in chat
    Route::post('/chat/{requestID}', [HandoverMessageController::class, 'store'])
        ->name('handover.chat.store');
    
    // Fetch messages via AJAX (for real-time updates)
    Route::get('/chat/{requestID}/fetch', [HandoverMessageController::class, 'fetchMessages'])
        ->name('handover.chat.fetch');
});







/*----------------- TEMP ROUTES -------------------*/
// DIGITAL ITEM TAG
Route::prefix('tag')->group(function () {
    Route::get('/scan', function () {
        return view('tag.scan');
    })->name('tag.scan');

    Route::get('/register', function () {
        return view('tag.register');
    })->name('tag.register');

    Route::get('/my', function () {
        return view('tag.my');
    })->name('tag.my');
});

// HELP CENTER
Route::prefix('help')->group(function () {
    Route::get('/faq', function () {
        return view('help.faq');
    })->name('help.faq');

    Route::get('/feedback', function () {
        return view('help.feedback');
    })->name('help.feedback');
});

// COMMUNITY FORUM
Route::get('/forum', function () {
    return view('forum.index'); // Blade file name here
})->name('forum.index');