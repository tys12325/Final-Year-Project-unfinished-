<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHP.php to edit this template
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Twilio\Rest\Client;
use App\Models\User;
use Carbon\Carbon;

class OTPController extends Controller {

    private $twilio;
    private $verifySid;

    public function __construct() {
        $this->twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
        $this->verifySid = env('TWILIO_VERIFY_SID');
    }

    // Step 1: Send OTP only if phone is not verified

    public function sendOTP(Request $request) {
        
        $request->validate([
            'phone' => [
                'required',
                'string',
                'regex:/^\+?[0-9]{10,15}$/', // Ensures only numbers (optionally with + at the start)
            ],
                ], [
            'phone.required' => 'Phone number is required!',
            'phone.regex' => 'Please enter a valid phone number (10-15 digits only).',
        ]);

        $phone = trim($request->phone);
       
        if (str_starts_with($phone, '+')) {
            $phone = substr($phone, 1);
        }
        $user = User::where('phone', $phone)->first();
        
        if ($user && $user->phone_verified_at) {
            return back()->with('error', 'This phone number is already verified.');
        }
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }


        try {
            $this->twilio->verify->v2->services($this->verifySid)
                    ->verifications
                    ->create($phone, "sms");
            session(['phone' => $phone]); 
            return back()->with('message', 'OTP Sent Successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send OTP: ' . $e->getMessage());
        }
    }

    public function verifyOTP(Request $request) {
        $phone = session('phone');

        $otp = $request->otp;
        
        if (!str_starts_with($phone, '+')) {
            $phone = '+' . $phone;
        }

        $verificationCheck = $this->twilio->verify->v2->services($this->verifySid)
                ->verificationChecks
                ->create([
            'to' => $phone,
            'code' => $otp,
        ]);

        if ($verificationCheck->status === 'approved') {
            if (str_starts_with($phone, '+')) {
                $phone = substr($phone, 1);
            }
            $user = User::where('phone', $phone)->first();
            $user->phone_verified_at = now();
            $user->save();
            return redirect(route('userSetting'))->back()->with('message', '✅ OTP Verified Successfully!');


  
        } else {
            return back()->with('message', '❌ Invalid OTP!');
        }
    }
}
