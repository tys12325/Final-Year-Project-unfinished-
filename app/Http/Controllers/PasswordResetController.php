<?php

namespace App\Http\Controllers;

use App\Mail\PasswordResetMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller {

   
    public function requestReset()
{
    $user = Auth::user();

    if (!$user) {
        return back()->withErrors(['error' => 'User not found.']);
    }

  
    $token = Str::random(64);

    // Store token in the password_resets table (DO NOT hash the token)
    DB::table('password_reset_tokens')->updateOrInsert(
        ['email' => $user->email],
        ['token' => Hash::make($token), 'created_at' => now()]

    );

 
    $resetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);

  
    Mail::to($user->email)->send(new PasswordResetMail($resetUrl));

    return back()->with('success', 'Password reset link has been sent to your email.');
}


   
    public function showResetForm($token, Request $request)
{
    $email = $request->query('email'); 

     
    return view('auth.reset-password', [
        'token' => $token,
        'email' => $email, 
    ]);
    
}

  
    public function resetPassword(Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
        'token' => 'required',
    ]);

    
    $resetData = DB::table('password_reset_tokens')->where('email', $request->email)->first();

    if (!$resetData || !Hash::check($request->token, $resetData->token)) {
        return back()->withErrors(['email' => 'Invalid or expired token.']);
    }

    
    $user = \App\Models\User::where('email', $request->email)->first();
    if (!$user) {
        return back()->withErrors(['email' => 'User not found.']);
    }

    
    $user->update(['password' => bcrypt($request->password)]);

    
    DB::table('password_reset_tokens')->where('email', $request->email)->delete();

    return redirect()->route('login')->with('status', 'Password has been reset!');
}

}
