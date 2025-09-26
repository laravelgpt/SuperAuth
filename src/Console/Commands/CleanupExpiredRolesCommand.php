<?php

namespace SuperAuth\Console\Commands;

use Illuminate\Console\Command;
use SuperAuth\Models\Role;
use SuperAuth\Services\RoleManagementService;

class CleanupExpiredRolesCommand extends Command
{
    protected $signature = 'superauth:cleanup-expired-roles {--dry-run : Show what would be deleted without actually deleting}';
    protected $description = 'Clean up expired roles and permissions';

    protected $roleManagementService;

    public function __construct(RoleManagementService $roleManagementService)
    {
        parent::__construct();
        $this->roleManagementService = $roleManagementService;
    }

    public function handle()
    {
        $this->info('🧹 Cleaning up expired roles...');

        $expiredRoles = Role::getExpiredRoles();
        $expiringRoles = Role::getExpiringRoles(7);

        if ($expiredRoles->isEmpty() && $expiringRoles->isEmpty()) {
            $this->info('✅ No expired or expiring roles found.');
            return;
        }

        // Show expired roles
        if ($expiredRoles->isNotEmpty()) {
            $this->warn('📅 Expired roles:');
            foreach ($expiredRoles as $role) {
                $this->line("  - {$role->name} (expired: {$role->expires_at->format('Y-m-d H:i:s')})");
            }
        }

        // Show expiring roles
        if ($expiringRoles->isNotEmpty()) {
            $this->warn('⏰ Expiring roles (next 7 days):');
            foreach ($expiringRoles as $role) {
                $this->line("  - {$role->name} (expires: {$role->expires_at->format('Y-m-d H:i:s')})");
            }
        }

        if ($this->option('dry-run')) {
            $this->info('🔍 Dry run mode - no changes made.');
            return;
        }

        if ($this->confirm('Do you want to proceed with cleanup?')) {
            $deletedCount = $this->roleManagementService->cleanupExpiredRoles();
            $this->info("✅ Cleaned up {$deletedCount} expired roles.");
        } else {
            $this->info('❌ Cleanup cancelled.');
        }
    }
}
