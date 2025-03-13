<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\User; 

class EmailUpdateController extends Controller
{
    public function showUpdateForm()
    {
        return view('auth.update-email'); // Create this Blade file
    }
    public function updateWorkGmail(Request $request)
    {
        $user = Auth::user();

        // ✅ Validate new email (ensure uniqueness)
        $validator = \Validator::make($request->all(), [
            'work_email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
                function ($attribute, $value, $fail) {
                    if (preg_match('/@gmail\.com$/i', $value)) {
                        session()->flash('error', 'Please update into your own work email ("xxx@company.com"), NOT a personal Gmail ("xxx@gmail.com").');
                        $fail('Please update into your own work email ("xxx@company.com"), NOT a personal Gmail ("xxx@gmail.com").');
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->route('email.update.form')->withErrors($validator)->withInput();
        }



        // ✅ Update email & mark as unverified
        $user->email = $request->work_email;
        $user->email_verified_at = null;
        $user->save();

        // ✅ Send verification email
        $user->sendEmailVerificationNotification();

        // Logout and redirect to login
        
        return redirect('/login')->with('success', 'Email updated successfully. Please log in with your new credentials.');
     
    }
}
