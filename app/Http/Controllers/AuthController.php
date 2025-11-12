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
            'userEmail'=> 'required|email',
            'password' => 'required'
        ]);

        $credentials = [
            'userEmail' => $request->input('userEmail'),
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::user();

            // Redirect based on user role
            if ($user->userRole === 'Admin') {
                return redirect()->route('admin.admin_dashboard')
                    ->with('status', 'Welcome back, Admin!');
            }

            // Normal user
            return redirect()->route('user.dashboard')
                ->with('status', 'Login successful!');
        }

        return back()->withInput()
                    ->with('status',
                           'Invalid credentials');
    }
}
