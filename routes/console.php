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

// SuperAuth Console Commands
Artisan::command('superauth:install', function () {
    $this->info('Installing SuperAuth Authentication System...');
    
    // Publish migrations
    $this->call('vendor:publish', [
        '--provider' => 'SuperAuth\SuperAuthServiceProvider',
        '--tag' => 'superauth-migrations'
    ]);
    
    // Publish config
    $this->call('vendor:publish', [
        '--provider' => 'SuperAuth\SuperAuthServiceProvider',
        '--tag' => 'superauth-config'
    ]);
    
    // Publish views
    $this->call('vendor:publish', [
        '--provider' => 'SuperAuth\SuperAuthServiceProvider',
        '--tag' => 'superauth-views'
    ]);
    
    // Publish assets
    $this->call('vendor:publish', [
        '--provider' => 'SuperAuth\SuperAuthServiceProvider',
        '--tag' => 'superauth-assets'
    ]);
    
    // Run migrations
    $this->call('migrate');
    
    // Create default roles and permissions
    $this->call('superauth:create-default-roles');
    
    $this->info('SuperAuth Authentication System installed successfully!');
})->purpose('Install SuperAuth Authentication System');

Artisan::command('superauth:create-default-roles', function () {
    $this->info('Creating default roles and permissions...');
    
    $roleManagementService = app(\SuperAuth\Services\RoleManagementService::class);
    $roleManagementService->createDefaultRolesAndPermissions();
    
    $this->info('Default roles and permissions created successfully!');
})->purpose('Create default roles and permissions');

Artisan::command('superauth:cleanup-expired-roles', function () {
    $this->info('Cleaning up expired role assignments...');
    
    $roleManagementService = app(\SuperAuth\Services\RoleManagementService::class);
    $cleanedCount = $roleManagementService->cleanupExpiredRoles();
    
    $this->info("Cleaned up {$cleanedCount} expired role assignments.");
})->purpose('Clean up expired role assignments');

Artisan::command('superauth:role-stats', function () {
    $this->info('SuperAuth Role Statistics:');
    
    $roleManagementService = app(\SuperAuth\Services\RoleManagementService::class);
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

Artisan::command('superauth:test-notifications', function () {
    $this->info('Testing SuperAuth notification system...');
    
    $notificationService = app(\SuperAuth\Services\NotificationTestingService::class);
    $results = $notificationService->testAllChannels();
    
    $this->info('Notification Test Results:');
    foreach ($results as $channel => $result) {
        $status = $result['success'] ? '✅' : '❌';
        $this->line("  {$status} {$channel}: {$result['message']}");
    }
})->purpose('Test SuperAuth notification system');

Artisan::command('superauth:ai-insights', function () {
    $this->info('Generating AI insights...');
    
    $aiService = app(\SuperAuth\Services\AiAgentService::class);
    $insights = $aiService->generateInsights();
    
    $this->info('AI Insights:');
    foreach ($insights as $insight) {
        $this->line("  • {$insight['type']}: {$insight['message']}");
    }
})->purpose('Generate AI insights for security monitoring');