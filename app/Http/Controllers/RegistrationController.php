<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    public function showForm(): View
    {
        return view('auth.register');
    }

    public function processForm(Request $request): RedirectResponse
    {
        $request->validate([
            'userName' => 'required|string|max:255',
            'userEmail' => 'required|string|max:255|email|unique:users,userEmail',
            'password' => 'required|string|min:3|confirmed'
        ]);

        $user = User::create([
            'userName' => $request -> userName,
            'userEmail' => $request -> userEmail,
            'password' =>Hash::make($request -> password)
        ]);

        return redirect()->route('login')
                         ->with('status',
                                'Registration successful, you can now login!');
    }

}
