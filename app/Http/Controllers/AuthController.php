<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'userEmail'=> 'required',
            'password' => 'required'
        ]);

        $credentials = $request->only('userEmail', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('user.dashboard') 
                ->with('status',
                       'Login successful!');
        }

        return back()->withInput()
                    ->with('status',
                           'Invalid credentials');
    }
}
