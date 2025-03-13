<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class PasswordChangeController extends Controller
{
    // Keep your existing method for manual password update
    public function showChangePasswordForm()
    {
        return view('auth.password-change'); // Your existing view
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        if (Hash::check($request->new_password, $user->password)) {
            return back()->withErrors(['new_password' => 'New password cannot be the same as the current password.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->must_change_password = false;
        $user->save();

        if ($user->isDefaultEmail()) {
            return redirect()->route('email.update.form')->with('success', 'Password changed successfully. Please update your work email.');
        }

        if ($user->hasVerifiedEmail()) {
            Auth::logout();
            return redirect()->route('login')->with('success', 'Password updated successfully. Please log in again.');
        }

        return redirect()->route('email.update.form')->with('success', 'Password changed successfully. Please update work Email too.');
    }

    // 🔹 SEND PASSWORD RESET EMAIL
    public function sendResetLinkEmail(Request $request)
    {
        $request->merge(['email' => trim(strtolower($request->email))]);
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['success' => 'Password reset link sent to your email.'])
            : back()->withErrors(['email' => __($status)]);
    }

    // 🔹 SHOW RESET PASSWORD FORM (from the email link)
    public function showResetPasswordForm($token)
    {
        return view('auth.password-reset', ['token' => $token]);
    }

    // 🔹 UPDATE PASSWORD (from reset link)
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Fetch the token data from password_reset_tokens table
        $tokenData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$tokenData) {
            return back()->withErrors(['email' => 'This password reset token is invalid or expired.']);
        }

        // Verify the hashed token
        if (!Hash::check($request->token, $tokenData->token)) {
            return back()->withErrors(['email' => 'This password reset token is invalid.']);
        }

        // Get the user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        // Prevent user from setting the same password again
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'New password cannot be the same as the current password.']);
        }

        // Update the password
        $user->password = Hash::make($request->password);
        $user->save();

        // Delete the used token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Your password has been reset. Please log in.');
    }

}
