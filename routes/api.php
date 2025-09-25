<?php

use Illuminate\Support\Facades\Route;
use SuperAuth\Http\Controllers\AuthController;
use SuperAuth\Http\Controllers\SocialAuthController;
use SuperAuth\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {
    // Guest routes
    Route::middleware('guest')->group(function () {
        // Login API
        Route::post('/login', [AuthController::class, 'apiLogin'])->name('api.login');
        
        // Registration API
        Route::post('/register', [AuthController::class, 'apiRegister'])->name('api.register');
        
        // Password reset API
        Route::post('/forgot-password', [AuthController::class, 'apiSendResetLink'])->name('api.password.request');
        Route::post('/reset-password', [AuthController::class, 'apiResetPassword'])->name('api.password.reset');
        
        // OTP API
        Route::post('/otp-verify', [AuthController::class, 'apiVerifyOtp'])->name('api.otp.verify');
        Route::post('/otp/resend', [AuthController::class, 'apiResendOtp'])->name('api.otp.resend');
        
        // Social authentication API
        Route::post('/social/{provider}', [SocialAuthController::class, 'apiRedirectToProvider'])->name('api.social.redirect');
        Route::post('/social/{provider}/callback', [SocialAuthController::class, 'apiHandleProviderCallback'])->name('api.social.callback');
    });
    
    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        // Logout API
        Route::post('/logout', [AuthController::class, 'apiLogout'])->name('api.logout');
        
        // User profile API
        Route::get('/user', [AuthController::class, 'apiUser'])->name('api.user');
        Route::put('/user', [AuthController::class, 'apiUpdateUser'])->name('api.user.update');
        Route::post('/user/avatar', [AuthController::class, 'apiUpdateAvatar'])->name('api.user.avatar');
        
        // Password change API
        Route::post('/change-password', [AuthController::class, 'apiChangePassword'])->name('api.change-password');
        
        // Social account management API
        Route::get('/social-accounts', [AuthController::class, 'apiSocialAccounts'])->name('api.social-accounts');
        Route::delete('/social-accounts/{provider}', [AuthController::class, 'apiDisconnectSocial'])->name('api.social.disconnect');
        
        // Admin API routes
        Route::prefix('admin')->middleware('role:admin')->group(function () {
            // User management API
            Route::get('/users', [AdminController::class, 'apiUsers'])->name('api.admin.users');
            Route::get('/users/{user}', [AdminController::class, 'apiShowUser'])->name('api.admin.users.show');
            Route::put('/users/{user}', [AdminController::class, 'apiUpdateUser'])->name('api.admin.users.update');
            Route::delete('/users/{user}', [AdminController::class, 'apiDeleteUser'])->name('api.admin.users.delete');
            Route::post('/users/{user}/toggle-status', [AdminController::class, 'apiToggleUserStatus'])->name('api.admin.users.toggle-status');
            
            // Role management API
            Route::get('/roles', [AdminController::class, 'apiRoles'])->name('api.admin.roles');
            Route::post('/roles', [AdminController::class, 'apiCreateRole'])->name('api.admin.roles.create');
            Route::put('/roles/{role}', [AdminController::class, 'apiUpdateRole'])->name('api.admin.roles.update');
            Route::delete('/roles/{role}', [AdminController::class, 'apiDeleteRole'])->name('api.admin.roles.delete');
            
            // User role assignment API
            Route::get('/users/{user}/roles', [AdminController::class, 'apiUserRoles'])->name('api.admin.users.roles');
            Route::post('/users/{user}/roles', [AdminController::class, 'apiAssignRole'])->name('api.admin.users.roles.assign');
            Route::delete('/users/{user}/roles/{role}', [AdminController::class, 'apiRemoveRole'])->name('api.admin.users.roles.remove');
            
            // Analytics API
            Route::get('/analytics', [AdminController::class, 'apiAnalytics'])->name('api.admin.analytics');
            Route::get('/analytics/users', [AdminController::class, 'apiUserAnalytics'])->name('api.admin.analytics.users');
            Route::get('/analytics/security', [AdminController::class, 'apiSecurityAnalytics'])->name('api.admin.analytics.security');
            
            // AI Dashboard API
            Route::get('/ai-dashboard', [AdminController::class, 'apiAiDashboard'])->name('api.admin.ai-dashboard');
            Route::get('/ai-insights', [AdminController::class, 'apiAiInsights'])->name('api.admin.ai-insights');
            Route::get('/security-monitoring', [AdminController::class, 'apiSecurityMonitoring'])->name('api.admin.security-monitoring');
            
            // Notifications API
            Route::get('/notifications', [AdminController::class, 'apiNotifications'])->name('api.admin.notifications');
            Route::post('/notifications/send', [AdminController::class, 'apiSendNotification'])->name('api.admin.notifications.send');
            Route::put('/notifications/{notification}/read', [AdminController::class, 'apiMarkNotificationRead'])->name('api.admin.notifications.read');
        });
    });
});

// Public API routes (no authentication required)
Route::prefix('public')->group(function () {
    // Health check
    Route::get('/health', function () {
        return response()->json([
            'status' => 'ok',
            'timestamp' => now(),
            'version' => '1.0.0'
        ]);
    })->name('api.public.health');
    
    // System status
    Route::get('/status', function () {
        return response()->json([
            'system' => 'SuperAuth',
            'version' => '1.0.0',
            'status' => 'operational',
            'features' => [
                'authentication' => true,
                'social_login' => true,
                'otp_verification' => true,
                'password_breach_check' => true,
                'ai_monitoring' => true,
                'multi_channel_notifications' => true
            ]
        ]);
    })->name('api.public.status');
});
