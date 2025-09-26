<?php

namespace SuperAuth\Console\Commands;

use Illuminate\Console\Command;
use SuperAuth\Models\Role;
use SuperAuth\Models\Permission;
use SuperAuth\Models\User;

class RoleStatsCommand extends Command
{
    protected $signature = 'superauth:role-stats {--format=table : Output format (table, json, csv)}';
    protected $description = 'Display role and permission statistics';

    public function handle()
    {
        $this->info('📊 SuperAuth Role and Permission Statistics');
        $this->line('');

        $format = $this->option('format');

        switch ($format) {
            case 'json':
                $this->displayJsonStats();
                break;
            case 'csv':
                $this->displayCsvStats();
                break;
            default:
                $this->displayTableStats();
        }
    }

    protected function displayTableStats()
    {
        // Role statistics
        $this->info('👥 Role Statistics:');
        $roleStats = Role::getRoleStats();
        
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Roles', $roleStats['total']],
                ['Active Roles', $roleStats['active']],
                ['Inactive Roles', $roleStats['inactive']],
                ['Expired Roles', $roleStats['expired']],
                ['Expiring Soon', $roleStats['expiring_soon']],
            ]
        );

        // Permission statistics
        $this->info('🔐 Permission Statistics:');
        $permissionStats = Permission::getPermissionStats();
        
        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Permissions', $permissionStats['total']],
                ['System Permissions', $permissionStats['system']],
                ['Custom Permissions', $permissionStats['custom']],
                ['Feature Permissions', $permissionStats['feature']],
            ]
        );

        // User statistics
        $this->info('👤 User Statistics:');
        $userStats = [
            'Total Users' => User::count(),
            'Active Users' => User::active()->count(),
            'Users with Roles' => User::whereHas('roles')->count(),
            'Users with Permissions' => User::whereHas('permissions')->count(),
        ];
        
        $this->table(
            ['Metric', 'Count'],
            collect($userStats)->map(fn($count, $metric) => [$metric, $count])->toArray()
        );

        // Top roles
        $this->info('🏆 Top Roles by User Count:');
        $topRoles = Role::withCount('users')
            ->orderBy('users_count', 'desc')
            ->limit(5)
            ->get();
        
        $this->table(
            ['Role', 'User Count', 'Level', 'Status'],
            $topRoles->map(fn($role) => [
                $role->name,
                $role->users_count,
                $role->level,
                $role->getStatusText(),
            ])->toArray()
        );
    }

    protected function displayJsonStats()
    {
        $stats = [
            'roles' => Role::getRoleStats(),
            'permissions' => Permission::getPermissionStats(),
            'users' => [
                'total' => User::count(),
                'active' => User::active()->count(),
                'with_roles' => User::whereHas('roles')->count(),
                'with_permissions' => User::whereHas('permissions')->count(),
            ],
            'top_roles' => Role::withCount('users')
                ->orderBy('users_count', 'desc')
                ->limit(5)
                ->get()
                ->map(fn($role) => [
                    'name' => $role->name,
                    'user_count' => $role->users_count,
                    'level' => $role->level,
                    'status' => $role->getStatusText(),
                ]),
        ];

        $this->line(json_encode($stats, JSON_PRETTY_PRINT));
    }

    protected function displayCsvStats()
    {
        $this->line('Metric,Count');
        
        // Role statistics
        $roleStats = Role::getRoleStats();
        foreach ($roleStats as $metric => $count) {
            $this->line("Role {$metric},{$count}");
        }
        
        // Permission statistics
        $permissionStats = Permission::getPermissionStats();
        foreach ($permissionStats as $metric => $count) {
            $this->line("Permission {$metric},{$count}");
        }
        
        // User statistics
        $this->line('User Total,' . User::count());
        $this->line('User Active,' . User::active()->count());
        $this->line('User With Roles,' . User::whereHas('roles')->count());
        $this->line('User With Permissions,' . User::whereHas('permissions')->count());
    }
}
