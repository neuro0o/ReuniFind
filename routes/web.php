<?php

use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

// LANDING
Route::get('/', function () {
    return view('landing');
});

/*----------------- AUTH ROUTES -------------------*/

// REGISTER
Route::get('register', [RegistrationController::class, 'showForm'])
    ->name('register');


// LOGIN
Route::get('/login', function() {
    return view('auth.login');
})->name('login');

/*----------------- TEMP ROUTES -------------------*/
// HOME @ DASHBOARD
Route::get('/dashboard', function () {
    return view('dashboard'); // Blade file name here
})->name('dashboard');

// LOST & FOUND REPORT
Route::prefix('report')->group(function () {
    Route::get('/lost', function () {
        return view('report.lost');
    })->name('report.lost');

    Route::get('/found', function () {
        return view('report.found');
    })->name('report.found');

    Route::get('/view', function () {
        return view('report.view');
    })->name('report.view');

    Route::get('/matchmaking', function () {
        return view('report.matchmaking');
    })->name('report.matchmaking');

    Route::get('/my', function () {
        return view('report.my');
    })->name('report.my');
});


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
