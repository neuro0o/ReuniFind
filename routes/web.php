<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HandoverRequestController;
use App\Http\Controllers\HandoverMessageController;
use App\Http\Controllers\ItemReportController;
use App\Http\Controllers\RegistrationController;
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


/*----------------- ADMIN ROUTES -------------------*/
Route::middleware(['auth', 'isAdmin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.admin_dashboard');

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
        ->middleware('auth')
        ->name('handover.opposite_reports');;
    
    // Store new handover request
    Route::post('/store', [HandoverRequestController::class, 'store'])
        ->name('handover.store');
        
    Route::post('/instant/{reportID}', [HandoverRequestController::class, 'instantHandover'])
        ->name('handover.instant');

    // Cancel handover request (sender only)
    Route::delete('/handover/{id}/cancel', [HandoverRequestController::class, 'cancel'])
        ->name('handover.cancel')
        ->middleware('auth');

    // Show details of a handover
    Route::get('/{id}', [HandoverRequestController::class, 'show'])
        ->name('handover.show');

    // Update handover status (approve, reject, complete)
    Route::patch('/{id}/update', [HandoverRequestController::class, 'update'])
        ->name('handover.update');

    /* ----- CHAT MESSAGES ----- */
    // Show chat page for a handover
    // Route::get('/{handoverID}/chat', [HandoverMessageController::class, 'show'])
    //     ->name('handover.chat.show');
    
    Route::get('/handover/chat', function () {
        return 'Chat placeholder page';
    })->name('handover.chat');

    // Send message in chat
    Route::post('/{handoverID}/chat', [HandoverMessageController::class, 'store'])
        ->name('handover.chat.store');
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