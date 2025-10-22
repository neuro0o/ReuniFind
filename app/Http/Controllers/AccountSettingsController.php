<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AccountSettingsController extends Controller
{
    // SHOW SETTINGS PAGE
    public function index()
    {
        $user = Auth::user();
        return view('user.account_settings', compact('user'));
    }

    // UPDATE PROFILE INFO (name, email, contact, profile image)
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'userName' => 'required|string|max:255',
            'userEmail' => 'required|email|max:255|unique:users,userEmail,' . $user->userID . ',userID',
            'contactInfo' => 'nullable|string|max:50',
            'profileImg' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle profile image upload
        if ($request->hasFile('profileImg')) {
            // Delete old image if exists
            if ($user->profileImg && Storage::disk('public')->exists($user->profileImg)) {
                Storage::disk('public')->delete($user->profileImg);
            }

            $path = $request->file('profileImg')->store('/images/profiles', 'public');
            $validated['profileImg'] = $path;
        }

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    // UPDATE PASSWORD
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', 'Password updated successfully.');
    }

    // AJAX modal
    public function modal()
    {
        $user = auth()->user();
        return view('user.partials.account_editModal', compact('user'));
    }
}
