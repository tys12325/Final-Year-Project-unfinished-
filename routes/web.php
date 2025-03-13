<?php
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\EmailVerificationController;

use App\Http\Controllers\OTPController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\CourseImportController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FavoriteController;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\PasswordChangeController;
use App\Http\Controllers\Auth\EmailUpdateController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LogController;

Route::get('/', function () {
    return view('user.userSetting');
})->name('userP');


Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/reset-password', [PasswordResetController::class, 'resetPassword'])
    ->name('password.update');



Route::middleware(['auth'])->group(function () {
 
    Route::get('/userProfile', [LoginController::class, 'showProfile'])->name('userProfile');
    Route::get('/userProfileSetting', [LoginController::class, 'showProfileSetting'])->name('userSetting');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/dashboard/reset-password', [PasswordResetController::class, 'requestReset'])->name('dashboard.password.reset');
    Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');

Route::post('/saveToFavorite', [FavoriteController::class, 'saveFavorite'])->name('save.favorite');
Route::get('/favoritesList', [FavoriteController::class, 'index'])->name('favorites');
Route::delete('/favorites/{courseID}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');

});






Route::get('/User Dashboard/User Favorite', function () {
    return view('user.userFavorite');
})->name('userFavorite');

Route::get('/User Dashboard/User Notification', function () {
    return view('user.userNotification');
})->name('userNotification');

Route::get('/User Dashboard/User UpcomingEvent', function () {
    return view('user.userUpcomingEvent');
})->name('userUpcomingEvent');



Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/login');
})->name('logout');






//Feedback
Route::post('/Feedback', [FeedbackController::class, 'store'])->name('feedback.store');
Route::post('/chatbot', [ChatbotController::class, 'getResponse']);
Route::get('/FeedBack Support', function () {
    return view('FeedbackAndSupport.feedBackSupport');
})->name('feedBackSupport');
Route::get('/FeedBack Support/FAQ', function () {
    return view('FeedbackAndSupport.FAQ');
})->name('FAQ');
Route::get('/FeedBack Support/Feedback', function () {
    return view('FeedbackAndSupport.Feedback');
})->name('feedback');
Route::get('/FeedBack Support/ChatBot', function () {
    return view('FeedbackAndSupport.chatbot');
})->name('chatbot');
Route::get('/rating/list', [RatingController::class, 'index'])->name('universities.indexes');
Route::get('/ratings/create/{uni}', [RatingController::class, 'create'])->name('ratings.create');



Route::get('/email/verify', function () {
    return view('emails.EmailVerify');
})->name('verification.form');

Route::post('/email/verification', [EmailVerificationController::class, 'sendVerificationEmail'])
    ->name('send.verification.email');

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verifyEmail'])
    ->middleware(['signed'])
    ->name('verification.verify');





//Route::post('/two-factor/enable', [TwoFactorAuthController::class, 'enable'])
//    ->middleware(['auth'])
//    ->name('two-factor.enable');
//
//
//Route::post('/two-factor/disable', [TwoFactorAuthController::class, 'disable'])
//    ->middleware(['auth'])
//    ->name('two-factor.disable');
//
//
//Route::post('/two-factor/confirm', [TwoFactorAuthController::class, 'confirm'])
//    ->middleware(['auth'])
//    ->name('two-factor.confirm');
//
//
//
//Route::get('/enable-2fa', function () {
//    $user = User::first();
//    if (!$user) {
//        return response()->json(['error' => 'User not found'], 404);
//    }
// $provider = app(TwoFactorAuthenticationProvider::class);
//    $enable2FA = new EnableTwoFactorAuthentication($provider);
//    $enable2FA($user);
//
//   
//    $secret = decrypt($user->two_factor_secret);
//
//    $qrCodeUrl = sprintf(
//        'otpauth://totp/%s:%s?secret=%s&issuer=%s&algorithm=SHA1&digits=6&period=30',
//        urlencode('YourAppName'),
//        urlencode($user->email),
//        $secret,
//        urlencode('YourAppName')
//    );
//
//    // Generate QR Code using Google Chart API
//    $qrCodeImage = 'https://chart.googleapis.com/chart?chs=300x300&cht=qr&chl=' . urlencode($qrCodeUrl);
//
//    // Return a Blade view
//    return view('enable-2fa', [
//        'secret_key' => $secret,
//        'qr_code_url' => $qrCodeUrl,
//        'qr_code_image' => $qrCodeImage
//            
//    ]);
//});
//
//
//
//Route::get('/verify-2fa/{code}', function ($code) {
//    $user = User::first(); // Fetch first user
//    $provider = app(TwoFactorAuthenticationProvider::class);
//    $confirm2FA = new ConfirmTwoFactorAuthentication($provider);
//
//    try {
//        $confirm2FA($user, $code);
//        return response()->json(['message' => '2FA Verified!']);
//    } catch (\Exception $e) {
//        return response()->json(['error' => $e->getMessage()], 400);
//    }
//});
//






Route::get('/otp', function () {
    return view('auth.otp');
})->name('otp');
Route::get('/verifyOTP', function () {
    return view('auth.verifyotp');
})->name('verifyotp');

Route::post('/send-otp', [OTPController::class, 'sendOTP'])->name('send-otp');
Route::post('/verify-otp', [OTPController::class, 'verifyOTP'])->name('verify-otp');


//Route::get('/application', function () {
//    return view('application');
//})->name('application');

//Route::get('/admin/Manage Application', function () {
//    return view('admin.ApplicationCheck');
//})->name('applicationManage');

//Route::get('/application', [applicationController::class, 'showUni'])->name('application.create');
//Route::post('/applicationSubmit', [applicationController::class, 'store'])->name('application.store');
//
//
//Route::get('/admin/applications', [AdminController::class, 'index'])->name('admin.applications');
//
//Route::get('/admin/applicationsFilter', [AdminController::class, 'filter'])->name('admin.filter');
//
//Route::post('/admin/applications/{id}/update-status', [AdminController::class, 'updateStatus'])->name('admin.updateStatus');
//Route::get('/admin/applications/{id}', [AdminController::class, 'show'])->name('admin.applications.show');
//
//Route::get('/application-tracking/{id}', [ApplicationTracking::class])->name('application-tracking');






Route::get('/admin/feedback-chart', [AdminController::class, 'feedbackChart'])->name('admin.feedbackChart');

Route::get('/admin/university-ranking', [AdminController::class, 'universityRanking'])->name('admin.universityRanking');


Route::get('/filter-results', [CourseController::class, 'filter'])->name('filter.results');





Route::view('/register', 'auth.register')->name('register');
Route::view('/login', 'auth.login')->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Authenticated User Routes
//Route::middleware('auth')->group(function () {

//});

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/filter-results', [CourseController::class, 'filter'])->name('filter.results');

// Admin-Only Routes
Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('/adminDashboard', function () {
        return view('adminDashboard');
    })->name('adminDashboard');

    Route::get('/import', function () {
        return view('import');
    });

    Route::post('/courses/import', [CourseImportController::class, 'import'])->name('courses.import');

    // University Management
    Route::get('/universities', [UniversityController::class, 'index'])->name('universities.index');
    Route::match(['put', 'post'], '/universities/{id}/update', [UniversityController::class, 'update']);
    Route::get('/universities/create', [UniversityController::class, 'create'])->name('universities.create');
    Route::post('/universities/store', [UniversityController::class, 'store'])->name('universities.store');
    Route::delete('/universities/{id}', [UniversityController::class, 'destroy'])->name('universities.destroy');


    Route::get('/universities/{uniID}/courses', [CourseController::class, 'showByUniversity'])->name('courses.byUniversity');
    Route::match(['put', 'post'], '/universities/{uniID}/courses/{courseID}/update', [UniversityController::class, 'updateCourse']);

    // Course Management
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::match(['put', 'post'], '/courses/{courseID}/update', [CourseController::class, 'update'])->name('courses.update');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->name('courses.destroy');


});


// Routes that require email verification
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Email Verification Routes
Route::get('/email/verify', function () {
    if (Auth::user()->hasVerifiedEmail()) {
        return redirect('/dashboard'); // Redirect verified users
    }
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::post('/email/send-verification', [EmailVerificationController::class, 'sendVerificationEmail'])->name('email.sendVerification');

// Verification URL Handling
Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verifyEmail'])
    ->middleware(['signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.form');


Route::get('/filter-results', [CourseController::class, 'filter'])->name('filter.results');

Route::get('/password/change', [PasswordChangeController::class, 'showChangePasswordForm'])
    ->name('password.change')
    ->middleware('auth'); // Ensures only logged-in users can access

Route::post('/password/change', [PasswordChangeController::class, 'updatePassword'])
    ->name('password.update')
    ->middleware('auth'); // Ensures only logged-in users can update their password


Route::get('/email/update', [EmailUpdateController::class, 'showUpdateForm'])
        ->name('email.update.form')
        ->middleware('auth');

Route::post('/email/update', [EmailUpdateController::class, 'updateWorkGmail'])
        ->name('email.update')
        ->middleware('auth');

Route::post('/update-username', [AdminController::class, 'updateUsername'])->name('update.username');

Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
Route::get('/password/reset/{token}', [PasswordChangeController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/password/reset', [PasswordChangeController::class, 'resetPassword'])->name('password.update.reset');

Route::get('/forgot-gmail', [ForgotPasswordController::class, 'showForgotGmailForm'])->name('forgot.gmail');


Route::get('/report', [ReportController::class, 'index'])->name('report');

Route::get('/uni-logs', [LogController::class, 'uniIndex'])->name('logs.uni');
Route::get('/fetch-uni-logs', [LogController::class, 'fetchUniLogs'])->name('logs.fetch.uni');
Route::post('/clear-uni-logs', [LogController::class, 'clearUniLogs'])->name('logs.clear.uni');

// Course Logs
Route::get('/course-logs', [LogController::class, 'courseIndex'])->name('logs.course');
Route::get('/fetch-course-logs', [LogController::class, 'fetchCourseLogs'])->name('logs.fetch.course');
Route::post('/clear-course-logs', [LogController::class, 'clearCourseLogs'])->name('logs.clear.course');

Route::get('/courses/search', [CourseController::class, 'search'])->name('courses.search');
Route::get('/universities/search', [UniversityController::class, 'search'])->name('universities.search');