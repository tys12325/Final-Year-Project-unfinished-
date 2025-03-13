<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /**
     * Handle the user registration process.
     */
    public function register(Request $request)
    {
        // Validate user input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => [
                'required',
                'string',
                'regex:/^(\+?60|0)[1-9]\d{8,9}$/', 
            ],
        ]);

        // Create a new user with default role "user"
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'role' => 'user', // Default role
        ]);
        event(new Registered($user));
        // Auto-login the user after registration
        auth()->login($user);

        // Redirect to home with success message
        return redirect()->route('login')->with('success', 'Registration successful!');
    }
    
    
}
