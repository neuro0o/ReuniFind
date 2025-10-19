<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemReportController;
use App\Http\Controllers\RegistrationController;
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

// HOME @ DASHBOARD
Route::get('/dashboard', function () {
    return view('user.dashboard');
})->middleware('auth')->name('user.dashboard');


// LOST & FOUND REPORT
Route::middleware(['auth'])->prefix('item_report')->group(function () {
    Route::get('/report_lost', [ItemReportController::class, 'reportLost'])->name('item_report.report_lost');
    Route::post('/report_lost', [ItemReportController::class, 'processForm'])->name('item_report.store');

    Route::get('/report_found', [ItemReportController::class, 'reportFound'])->name('item_report.report_found');
    Route::post('/report_found', [ItemReportController::class, 'processForm'])->name('item_report.store');

    Route::get('/view', [ItemReportController::class, 'viewReports'])
    ->middleware('auth')
    ->name('item_report.view');

    Route::get('/reports/{id}', [ItemReportController::class, 'show'])->name('reports.show');


    // temp routes
    Route::get('/matchmaking', [ItemReportController::class, 'matchmaking'])
    ->name('item_report.report_matchmaking');
    Route::get('/my_report', [ItemReportController::class, 'myReports'])->name('item_report.my_report');
    Route::get('/edit/{id}', [ItemReportController::class, 'edit'])->name('item_report.edit');
    Route::put('/update/{id}', [ItemReportController::class, 'update'])->name('item_report.update');
    Route::delete('/delete/{id}', [ItemReportController::class, 'destroy'])->name('item_report.destroy');
});







/*----------------- TEMP ROUTES -------------------*/




// CHAT & REQUEST
Route::prefix('chat')->group(function () {
    Route::get('/claim', function () {
        return view('chat.claim');
    })->name('chat.claim');

    Route::get('/return', function () {
        return view('chat.return');
    })->name('chat.return');

    Route::get('/private', function () {
        return view('chat.private');
    })->name('chat.private');
});

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

// ACCOUNT SETTINGS
Route::get('/account', function () {
    return view('account.settings'); // Blade file name here
})->name('account.settings');

// LOGOUT
Route::get('/logout', function () {
    return view('landing'); // Blade file name here
})->name('logout');
