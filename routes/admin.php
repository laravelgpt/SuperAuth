<?php

use Illuminate\Support\Facades\Route;
use SuperAuth\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| Dedicated admin routes for SuperAuth package
*/

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/overview', [AdminController::class, 'overview'])->name('admin.overview');
    
    /*
    |--------------------------------------------------------------------------
    | User Management Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('users')->group(function () {
        Route::get('/', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/create', [AdminController::class, 'createUser'])->name('admin.users.create');
        Route::post('/', [AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::get('/{user}', [AdminController::class, 'showUser'])->name('admin.users.show');
        Route::get('/{user}/edit', [AdminController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/{user}', [AdminController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
        
        // User status management
        Route::post('/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('admin.users.toggle-status');
        Route::post('/{user}/activate', [AdminController::class, 'activateUser'])->name('admin.users.activate');
        Route::post('/{user}/deactivate', [AdminController::class, 'deactivateUser'])->name('admin.users.deactivate');
        
        // User role management
        Route::get('/{user}/roles', [AdminController::class, 'userRoles'])->name('admin.users.roles');
        Route::post('/{user}/roles', [AdminController::class, 'assignRole'])->name('admin.users.roles.assign');
        Route::delete('/{user}/roles/{role}', [AdminController::class, 'removeRole'])->name('admin.users.roles.remove');
        
        // User permissions
        Route::get('/{user}/permissions', [AdminController::class, 'userPermissions'])->name('admin.users.permissions');
        Route::post('/{user}/permissions', [AdminController::class, 'assignPermission'])->name('admin.users.permissions.assign');
        Route::delete('/{user}/permissions/{permission}', [AdminController::class, 'removePermission'])->name('admin.users.permissions.remove');
        
        // Bulk operations
        Route::post('/bulk/activate', [AdminController::class, 'bulkActivateUsers'])->name('admin.users.bulk.activate');
        Route::post('/bulk/deactivate', [AdminController::class, 'bulkDeactivateUsers'])->name('admin.users.bulk.deactivate');
        Route::post('/bulk/delete', [AdminController::class, 'bulkDeleteUsers'])->name('admin.users.bulk.delete');
        Route::post('/bulk/export', [AdminController::class, 'bulkExportUsers'])->name('admin.users.bulk.export');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Role Management Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('roles')->group(function () {
        Route::get('/', [AdminController::class, 'roles'])->name('admin.roles');
        Route::get('/create', [AdminController::class, 'createRole'])->name('admin.roles.create');
        Route::post('/', [AdminController::class, 'storeRole'])->name('admin.roles.store');
        Route::get('/{role}', [AdminController::class, 'showRole'])->name('admin.roles.show');
        Route::get('/{role}/edit', [AdminController::class, 'editRole'])->name('admin.roles.edit');
        Route::put('/{role}', [AdminController::class, 'updateRole'])->name('admin.roles.update');
        Route::delete('/{role}', [AdminController::class, 'deleteRole'])->name('admin.roles.delete');
        
        // Role permissions
        Route::get('/{role}/permissions', [AdminController::class, 'rolePermissions'])->name('admin.roles.permissions');
        Route::post('/{role}/permissions', [AdminController::class, 'assignPermissionToRole'])->name('admin.roles.permissions.assign');
        Route::delete('/{role}/permissions/{permission}', [AdminController::class, 'removePermissionFromRole'])->name('admin.roles.permissions.remove');
        
        // Role hierarchy
        Route::post('/{role}/move-up', [AdminController::class, 'moveRoleUp'])->name('admin.roles.move-up');
        Route::post('/{role}/move-down', [AdminController::class, 'moveRoleDown'])->name('admin.roles.move-down');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Permission Management Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('permissions')->group(function () {
        Route::get('/', [AdminController::class, 'permissions'])->name('admin.permissions');
        Route::get('/create', [AdminController::class, 'createPermission'])->name('admin.permissions.create');
        Route::post('/', [AdminController::class, 'storePermission'])->name('admin.permissions.store');
        Route::get('/{permission}', [AdminController::class, 'showPermission'])->name('admin.permissions.show');
        Route::get('/{permission}/edit', [AdminController::class, 'editPermission'])->name('admin.permissions.edit');
        Route::put('/{permission}', [AdminController::class, 'updatePermission'])->name('admin.permissions.update');
        Route::delete('/{permission}', [AdminController::class, 'deletePermission'])->name('admin.permissions.delete');
    });
    
    /*
    |--------------------------------------------------------------------------
    | AI Dashboard Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('ai')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'aiDashboard'])->name('admin.ai.dashboard');
        Route::get('/insights', [AdminController::class, 'aiInsights'])->name('admin.ai.insights');
        Route::get('/monitoring', [AdminController::class, 'aiMonitoring'])->name('admin.ai.monitoring');
        Route::get('/anomalies', [AdminController::class, 'aiAnomalies'])->name('admin.ai.anomalies');
        Route::get('/recommendations', [AdminController::class, 'aiRecommendations'])->name('admin.ai.recommendations');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Analytics Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('analytics')->group(function () {
        Route::get('/', [AdminController::class, 'analytics'])->name('admin.analytics');
        Route::get('/users', [AdminController::class, 'userAnalytics'])->name('admin.analytics.users');
        Route::get('/security', [AdminController::class, 'securityAnalytics'])->name('admin.analytics.security');
        Route::get('/performance', [AdminController::class, 'performanceAnalytics'])->name('admin.analytics.performance');
        Route::get('/notifications', [AdminController::class, 'notificationAnalytics'])->name('admin.analytics.notifications');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Security Monitoring Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('security')->group(function () {
        Route::get('/monitoring', [AdminController::class, 'securityMonitoring'])->name('admin.security.monitoring');
        Route::get('/threats', [AdminController::class, 'securityThreats'])->name('admin.security.threats');
        Route::get('/incidents', [AdminController::class, 'securityIncidents'])->name('admin.security.incidents');
        Route::get('/audit-log', [AdminController::class, 'auditLog'])->name('admin.security.audit-log');
        Route::get('/login-attempts', [AdminController::class, 'loginAttempts'])->name('admin.security.login-attempts');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Notification Management Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('notifications')->group(function () {
        Route::get('/', [AdminController::class, 'notifications'])->name('admin.notifications');
        Route::get('/create', [AdminController::class, 'createNotification'])->name('admin.notifications.create');
        Route::post('/', [AdminController::class, 'storeNotification'])->name('admin.notifications.store');
        Route::get('/{notification}', [AdminController::class, 'showNotification'])->name('admin.notifications.show');
        Route::put('/{notification}', [AdminController::class, 'updateNotification'])->name('admin.notifications.update');
        Route::delete('/{notification}', [AdminController::class, 'deleteNotification'])->name('admin.notifications.delete');
        
        // Notification sending
        Route::post('/send', [AdminController::class, 'sendNotification'])->name('admin.notifications.send');
        Route::post('/bulk-send', [AdminController::class, 'bulkSendNotification'])->name('admin.notifications.bulk-send');
    });
    
    /*
    |--------------------------------------------------------------------------
    | System Settings Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('settings')->group(function () {
        Route::get('/', [AdminController::class, 'settings'])->name('admin.settings');
        Route::put('/', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
        
        // Authentication settings
        Route::get('/auth', [AdminController::class, 'authSettings'])->name('admin.settings.auth');
        Route::put('/auth', [AdminController::class, 'updateAuthSettings'])->name('admin.settings.auth.update');
        
        // Security settings
        Route::get('/security', [AdminController::class, 'securitySettings'])->name('admin.settings.security');
        Route::put('/security', [AdminController::class, 'updateSecuritySettings'])->name('admin.settings.security.update');
        
        // Notification settings
        Route::get('/notifications', [AdminController::class, 'notificationSettings'])->name('admin.settings.notifications');
        Route::put('/notifications', [AdminController::class, 'updateNotificationSettings'])->name('admin.settings.notifications.update');
    });
});
