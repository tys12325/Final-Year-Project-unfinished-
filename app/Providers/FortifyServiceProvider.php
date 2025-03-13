<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Fortify;
use app\Models\User;

Fortify::loginResponse(function (Request $request) {
    $user = $request->user();
    
    if ($user->two_factor_secret) {
        return redirect()->route('two-factor.challenge'); // Require 2FA before accessing dashboard
    }

    return redirect()->route('userSetting');
});

