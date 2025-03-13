<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password'); // Create this view
    }
    public function showForgotGmailForm()
    {
        return view('auth.forgot-gmail');
    }
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? redirect('/login')->with(['success' => 'Password reset link sent to your email.'])
            : back()->withErrors(['email' => __($status)]);
    }
}
