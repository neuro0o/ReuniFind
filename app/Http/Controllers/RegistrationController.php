<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function showForm(): View
    {
        return view('auth.register');
    }
}
