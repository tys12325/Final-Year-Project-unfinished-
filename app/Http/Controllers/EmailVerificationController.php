<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use App\Models\User;

class EmailVerificationController extends Controller {

    public function sendVerificationEmail(Request $request) {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->with('error', 'Email not found. Please register first.');
        }

  
        $verificationUrl = URL::temporarySignedRoute(
                        'verification.verify',
                        Carbon::now()->addMinutes(3),                       
                        ['id' => $user->userId, 'hash' => sha1($user->email)]
        );

        Mail::raw("Click the link to verify your email: $verificationUrl in 3 minutes.", function ($message) use ($email) {
            $message->to($email)
                    ->subject('** VERIFY YOUR EMAIL **');
        });

        return back()->with('message', 'Verification email sent! Please check your inbox.');
    }

    public function verifyEmail(Request $request, $userId, $hash) {
        $user = User::findOrFail($userId);

   
        if ($user->email_verified_at !== null) {
            session(['Emailverify' => true]);
            if ($user->role === 'admin') {
                return redirect('/adminDashboard')->with('success', 'Email verified successfully!');
            }
            return redirect('/')->with('info', 'Your email has already been verified.');
        }

        
        if (!hash_equals(sha1($user->email), $hash)) {
            return redirect('/email/verify')->with('error', 'Invalid verification link.');
        }

      
        $user->email_verified_at = now();
        $user->save();
        if ($user->role === 'admin') {
            return redirect('/adminDashboard')->with('success', 'Email verified successfully!');
        }
            return redirect('/')->with('success', 'Email verified successfully!');
        }
}
