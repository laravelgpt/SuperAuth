<?php

use Illuminate\Support\Facades\Route;
use SuperAuth\Http\Controllers\AuthController;
use SuperAuth\Http\Controllers\SocialAuthController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
| Dedicated authentication routes for SuperAuth package
*/

Route::middleware('guest')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Login Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'login']);
    
    /*
    |--------------------------------------------------------------------------
    | Registration Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register');
    Route::post('/register', [AuthController::class, 'register']);
    
    /*
    |--------------------------------------------------------------------------
    | Password Reset Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('auth.password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('auth.password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    
    /*
    |--------------------------------------------------------------------------
    | OTP Verification Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/otp-verification', [AuthController::class, 'showOtpVerification'])->name('auth.otp.verification');
    Route::post('/otp-verification', [AuthController::class, 'verifyOtp']);
    Route::post('/otp/resend', [AuthController::class, 'resendOtp'])->name('auth.otp.resend');
    
    /*
    |--------------------------------------------------------------------------
    | Social Authentication Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/social/{provider}', [SocialAuthController::class, 'redirectToProvider'])->name('auth.social.redirect');
    Route::get('/social/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('auth.social.callback');
    
    /*
    |--------------------------------------------------------------------------
    | Email Verification Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/email/verify', [AuthController::class, 'showEmailVerification'])->name('auth.email.verify');
    Route::post('/email/verify', [AuthController::class, 'verifyEmail']);
    Route::post('/email/resend', [AuthController::class, 'resendEmailVerification'])->name('auth.email.resend');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Logout Route
    |--------------------------------------------------------------------------
    */
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    
    /*
    |--------------------------------------------------------------------------
    | Dashboard Route
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('auth.dashboard');
    
    /*
    |--------------------------------------------------------------------------
    | Profile Management Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [AuthController::class, 'profile'])->name('auth.profile');
    Route::post('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/profile/avatar', [AuthController::class, 'updateAvatar'])->name('auth.profile.avatar');
    Route::post('/profile/password', [AuthController::class, 'changePassword'])->name('auth.profile.password');
    
    /*
    |--------------------------------------------------------------------------
    | Social Account Management Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/social-accounts', [AuthController::class, 'socialAccounts'])->name('auth.social-accounts');
    Route::delete('/social-accounts/{provider}', [AuthController::class, 'disconnectSocial'])->name('auth.social.disconnect');
    
    /*
    |--------------------------------------------------------------------------
    | Security Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/security', [AuthController::class, 'security'])->name('auth.security');
    Route::post('/security/enable-2fa', [AuthController::class, 'enable2FA'])->name('auth.security.2fa.enable');
    Route::post('/security/disable-2fa', [AuthController::class, 'disable2FA'])->name('auth.security.2fa.disable');
    Route::get('/security/sessions', [AuthController::class, 'sessions'])->name('auth.security.sessions');
    Route::delete('/security/sessions/{session}', [AuthController::class, 'revokeSession'])->name('auth.security.sessions.revoke');
    
    /*
    |--------------------------------------------------------------------------
    | Notifications Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/notifications', [AuthController::class, 'notifications'])->name('auth.notifications');
    Route::put('/notifications/{notification}/read', [AuthController::class, 'markNotificationRead'])->name('auth.notifications.read');
    Route::put('/notifications/read-all', [AuthController::class, 'markAllNotificationsRead'])->name('auth.notifications.read-all');
});
