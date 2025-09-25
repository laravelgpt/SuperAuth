<?php

use Illuminate\Support\Facades\Route;
use SuperAuth\Http\Controllers\AuthController;
use SuperAuth\Http\Controllers\SocialAuthController;
use SuperAuth\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Login routes
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    // Registration routes
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    // Password reset routes
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    
    // OTP routes
    Route::get('/otp-verification', [AuthController::class, 'showOtpVerification'])->name('otp.verification');
    Route::post('/otp-verification', [AuthController::class, 'verifyOtp']);
    Route::post('/otp/resend', [AuthController::class, 'resendOtp'])->name('otp.resend');
    
    // Social authentication routes
    Route::get('/social/{provider}', [SocialAuthController::class, 'redirectToProvider'])->name('social.redirect');
    Route::get('/social/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('social.callback');
});

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    
    // Profile routes
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile', [AuthController::class, 'updateProfile']);
    
    // Admin routes
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('admin.users.show');
        Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
        Route::post('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle-status');
        
        // Role management routes
        Route::get('/roles', [AdminController::class, 'roles'])->name('admin.roles');
        Route::post('/roles', [AdminController::class, 'createRole'])->name('admin.roles.create');
        Route::put('/roles/{role}', [AdminController::class, 'updateRole'])->name('admin.roles.update');
        Route::delete('/roles/{role}', [AdminController::class, 'deleteRole'])->name('admin.roles.delete');
        
        // User role assignment routes
        Route::get('/users/{user}/roles', [AdminController::class, 'userRoles'])->name('admin.users.roles');
        Route::post('/users/{user}/roles', [AdminController::class, 'assignRole'])->name('admin.users.roles.assign');
        Route::delete('/users/{user}/roles/{role}', [AdminController::class, 'removeRole'])->name('admin.users.roles.remove');
        
        // AI Dashboard routes
        Route::get('/ai-dashboard', [AdminController::class, 'aiDashboard'])->name('admin.ai-dashboard');
        Route::get('/analytics', [AdminController::class, 'analytics'])->name('admin.analytics');
        Route::get('/security-monitoring', [AdminController::class, 'securityMonitoring'])->name('admin.security-monitoring');
    });
});