<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Multi-Vendor Auth Console Commands
Artisan::command('multi-vendor-auth:install', function () {
    $this->info('Installing Multi-Vendor Authentication System...');
    
    // Publish migrations
    $this->call('vendor:publish', [
        '--provider' => 'Vendor\MultiVendorAuth\MultiVendorAuthServiceProvider',
        '--tag' => 'migrations'
    ]);
    
    // Publish config
    $this->call('vendor:publish', [
        '--provider' => 'Vendor\MultiVendorAuth\MultiVendorAuthServiceProvider',
        '--tag' => 'config'
    ]);
    
    // Publish views
    $this->call('vendor:publish', [
        '--provider' => 'Vendor\MultiVendorAuth\MultiVendorAuthServiceProvider',
        '--tag' => 'views'
    ]);
    
    // Run migrations
    $this->call('migrate');
    
    // Create default roles and permissions
    $this->call('multi-vendor-auth:create-default-roles');
    
    $this->info('Multi-Vendor Authentication System installed successfully!');
})->purpose('Install Multi-Vendor Authentication System');

Artisan::command('multi-vendor-auth:create-default-roles', function () {
    $this->info('Creating default roles and permissions...');
    
    $roleManagementService = app(\Vendor\MultiVendorAuth\Services\RoleManagementService::class);
    $roleManagementService->createDefaultRolesAndPermissions();
    
    $this->info('Default roles and permissions created successfully!');
})->purpose('Create default roles and permissions');

Artisan::command('multi-vendor-auth:cleanup-expired-roles', function () {
    $this->info('Cleaning up expired role assignments...');
    
    $roleManagementService = app(\Vendor\MultiVendorAuth\Services\RoleManagementService::class);
    $cleanedCount = $roleManagementService->cleanupExpiredRoles();
    
    $this->info("Cleaned up {$cleanedCount} expired role assignments.");
})->purpose('Clean up expired role assignments');

Artisan::command('multi-vendor-auth:role-stats', function () {
    $this->info('Multi-Vendor Auth Role Statistics:');
    
    $roleManagementService = app(\Vendor\MultiVendorAuth\Services\RoleManagementService::class);
    $stats = $roleManagementService->getRoleStatistics();
    
    $this->table(
        ['Metric', 'Count'],
        [
            ['Total Roles', $stats['total_roles']],
            ['Active Roles', $stats['active_roles']],
            ['System Roles', $stats['system_roles']],
            ['Custom Roles', $stats['custom_roles']],
        ]
    );
    
    $this->info('Role Distribution:');
    foreach ($stats['role_distribution'] as $role) {
        $this->line("  • {$role['name']}: {$role['users_count']} users");
    }
})->purpose('Display role statistics');
