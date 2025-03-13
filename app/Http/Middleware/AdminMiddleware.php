<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            abort(403, 'User is not authenticated');
        }
        $user = Auth::user();
        // Debugging: Check user role
        $role = Auth::user()->role;
        if ($role !== 'admin') {
            abort(403, "Unauthorized access. Role: $role");
        }

         // If admin has not changed password, redirect to change password page
        if ($user->must_change_password) {
            return redirect()->route('password.change')->with('Please update password before accessing to admin content.');
        }       
        
        return $next($request);
    }

}
