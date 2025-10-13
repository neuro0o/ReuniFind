<?php

use Illuminate\Support\Facades\Route;

// LANDING
Route::get('/', function () {
    return view('landing');

    // TODO: REMOVE LATER
    // return view('user.index');
});

// AUTH
Route::get('/register', function() {
    return view('auth.register');
})->name('register');



