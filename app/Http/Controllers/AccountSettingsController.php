<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use App\Models\User; // make sure this is imported at the top

class AccountSettingsController extends Controller
{
    // SHOW SETTINGS PAGE
    public function index()
    {
        $user = Auth::user();
        return view('user.account_settings', compact('user'));
    }

    // UPDATE PROFILE INFO (name, email, password, contact, profile image)
    public function updateProfile(Request $request)
    {   
        /** @var User $user */
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'userName' => 'required|string|max:255',
            'userEmail' => 'required|email|max:255|unique:users,userEmail,' . $user->userID . ',userID',
            'new_password' => 'nullable|min:3|confirmed',
            'contactInfo' => 'nullable|string|max:50',
            'profileImg' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle profile image upload if provided
        if ($request->hasFile('profileImg')) {
            // Delete old image if exists
            if ($user->profileImg && Storage::disk('public')->exists($user->profileImg)) {
                Storage::disk('public')->delete($user->profileImg);
            }

            $path = $request->file('profileImg')->store('/images/profiles', 'public');
            $validated['profileImg'] = $path;
        }

        // Handle password change if provided
        if (!empty($request->new_password)) {
            $validated['password'] = Hash::make($request->new_password);
        }

        // Remove unnecessary fields (avoid validation key issues)
        unset($validated['new_password'], $validated['new_password_confirmation']);

        $user->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    // UPDATE PASSWORD
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:3|confirmed',
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', 'Password updated successfully.');
    }

    // DELETE ACCOUNT
    public function deleteAccount(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Prevent admins from deleting their account
        if ($user->userRole === 'Admin') {
            return back()->with('error', 'Admin accounts cannot be deleted.');
        }

        // Delete profile image if exists
        if ($user->profileImg && Storage::disk('public')->exists($user->profileImg)) {
            Storage::disk('public')->delete($user->profileImg);
        }

        // Log out before deleting
        Auth::logout();

        // Delete the user
        $user->delete();

        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to homepage or login
        return redirect('/')->with('success', 'Your account has been deleted successfully.');
    }



    // AJAX modal
    public function modal()
    {   
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return view('user.partials.account_editModal', compact('user'));
    }
}
