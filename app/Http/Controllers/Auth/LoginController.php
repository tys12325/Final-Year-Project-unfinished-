<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller {

    // Show the login form
    public function showLoginForm()
    {
        return view('auth.login'); // Ensure this view exists
    }

    // Handle the login attempt
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // 🔹 Check if admin must change password
            if ($user->role === 'admin' && $user->must_change_password) {
                return redirect()->route('password.change')->with('warning', 'You must change your password before proceeding.');
            }
            if ($user->role === 'admin' && $this->isDefaultEmail($user->email)) {
                return redirect()->route('email.update.form')
                    ->with('error', 'Please update your work email before proceeding.');
            }
            // 🔹 Check if the user's email is verified
            if (!$user->hasVerifiedEmail()) {
                return back()->with('error', 'Please verify your email before proceeding.');
            }

            
            if ($user->role === 'admin') {
                return redirect('/adminDashboard')->with('success', 'Login successful!');
            }



            // 🔹 Redirect based on user role
            return redirect('/')->with('success', 'Login successful!');
        }

        return back()->withErrors(['email' => 'Invalid email or password']);
    }

    private function isDefaultEmail($email)
    {
        return str_ends_with($email, '@gmail.com'); // Adjust based on your default email pattern
    }

    // Handle the logout
    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'Logged out successfully.');
    }

    public function showProfile()
    {
        $user = Auth::user(); 
        return view('user.userProfile', compact('user'));
    }
    public function showProfileSetting()
    {
        $user = Auth::user(); 
        return view('user.userSetting', compact('user'));
    }
}
