<?php

namespace SuperAuth\Console\Commands;

use Illuminate\Console\Command;
use SuperAuth\Models\Role;
use SuperAuth\Models\Permission;

class CreateDefaultRolesCommand extends Command
{
    protected $signature = 'superauth:create-default-roles';
    protected $description = 'Create default roles and permissions for SuperAuth';

    public function handle()
    {
        $this->info('👥 Creating default roles and permissions...');

        // Create default roles
        $this->createDefaultRoles();

        // Create default permissions
        $this->createDefaultPermissions();

        // Assign permissions to roles
        $this->assignPermissionsToRoles();

        $this->info('✅ Default roles and permissions created successfully!');
    }

    protected function createDefaultRoles()
    {
        $defaultRoles = Role::getDefaultRoles();

        foreach ($defaultRoles as $roleData) {
            $role = Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );

            $this->line("Created role: {$role->name}");
        }
    }

    protected function createDefaultPermissions()
    {
        $defaultPermissions = Permission::getDefaultPermissions();

        foreach ($defaultPermissions as $permissionData) {
            $permission = Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                array_merge($permissionData, ['is_system' => true])
            );

            $this->line("Created permission: {$permission->name}");
        }
    }

    protected function assignPermissionsToRoles()
    {
        $rolePermissions = [
            'super_admin' => Permission::all()->pluck('name')->toArray(),
            'admin' => [
                'users.create', 'users.read', 'users.update', 'users.delete',
                'roles.read', 'roles.update',
                'permissions.read',
                'admin.dashboard', 'admin.settings', 'admin.reports',
                'security.breach_check', 'security.password_strength', 'security.ai_monitoring',
            ],
            'moderator' => [
                'users.read', 'users.update',
                'admin.dashboard', 'admin.reports',
                'security.breach_check', 'security.password_strength',
            ],
            'user' => [
                'users.read',
                'security.breach_check', 'security.password_strength',
            ],
            'guest' => [
                'users.read',
            ],
        ];

        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->syncPermissions($permissions);
                $this->line("Assigned permissions to role: {$roleName}");
            }
        }
    }
}
