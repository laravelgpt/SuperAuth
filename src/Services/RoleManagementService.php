<?php

namespace SuperAuth\Services;

use SuperAuth\Models\Role;
use SuperAuth\Models\Permission;
use SuperAuth\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class RoleManagementService
{
    protected $cachePrefix = 'role_management:';
    protected $cacheTtl = 3600; // 1 hour

    public function createRole($data)
    {
        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => $data['guard_name'] ?? 'web',
            'display_name' => $data['display_name'] ?? $data['name'],
            'description' => $data['description'] ?? null,
            'level' => $data['level'] ?? 50,
            'is_active' => $data['is_active'] ?? true,
            'expires_at' => $data['expires_at'] ?? null,
            'created_by' => auth()->id(),
        ]);

        if (isset($data['permissions'])) {
            $this->assignPermissionsToRole($role, $data['permissions']);
        }

        $this->clearRoleCache();
        return $role;
    }

    public function updateRole($role, $data)
    {
        $role->update([
            'display_name' => $data['display_name'] ?? $role->display_name,
            'description' => $data['description'] ?? $role->description,
            'level' => $data['level'] ?? $role->level,
            'is_active' => $data['is_active'] ?? $role->is_active,
            'expires_at' => $data['expires_at'] ?? $role->expires_at,
            'updated_by' => auth()->id(),
        ]);

        if (isset($data['permissions'])) {
            $this->assignPermissionsToRole($role, $data['permissions']);
        }

        $this->clearRoleCache();
        return $role;
    }

    public function deleteRole($role)
    {
        // Check if role can be deleted
        if ($this->isSystemRole($role)) {
            throw new \Exception('Cannot delete system role');
        }

        if ($this->hasUsers($role)) {
            throw new \Exception('Cannot delete role with assigned users');
        }

        $role->delete();
        $this->clearRoleCache();
        return true;
    }

    public function assignPermissionsToRole($role, $permissions)
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        $role->syncPermissions($permissions);
        $this->clearRoleCache();
        return true;
    }

    public function removePermissionsFromRole($role, $permissions)
    {
        if (is_string($permissions)) {
            $permissions = [$permissions];
        }

        $role->revokePermissionTo($permissions);
        $this->clearRoleCache();
        return true;
    }

    public function assignRoleToUser($user, $role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if (!$role) {
            throw new \Exception('Role not found');
        }

        if ($this->userHasRole($user, $role->name)) {
            return false; // Already has role
        }

        $user->assignRole($role);
        $this->clearUserCache($user);
        return true;
    }

    public function removeRoleFromUser($user, $role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if (!$role) {
            throw new \Exception('Role not found');
        }

        $user->removeRole($role);
        $this->clearUserCache($user);
        return true;
    }

    public function assignPermissionToUser($user, $permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if (!$permission) {
            throw new \Exception('Permission not found');
        }

        $user->givePermissionTo($permission);
        $this->clearUserCache($user);
        return true;
    }

    public function removePermissionFromUser($user, $permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        if (!$permission) {
            throw new \Exception('Permission not found');
        }

        $user->revokePermissionTo($permission);
        $this->clearUserCache($user);
        return true;
    }

    public function getRoleHierarchy()
    {
        return Cache::remember($this->cachePrefix . 'hierarchy', $this->cacheTtl, function () {
            return Role::orderBy('level', 'desc')->get();
        });
    }

    public function getActiveRoles()
    {
        return Cache::remember($this->cachePrefix . 'active', $this->cacheTtl, function () {
            return Role::active()->notExpired()->get();
        });
    }

    public function getExpiredRoles()
    {
        return Role::expired()->get();
    }

    public function getExpiringRoles($days = 7)
    {
        return Role::getExpiringRoles($days);
    }

    public function getRoleStats()
    {
        return Cache::remember($this->cachePrefix . 'stats', $this->cacheTtl, function () {
            return Role::getRoleStats();
        });
    }

    public function getUserRoles($user)
    {
        return Cache::remember($this->cachePrefix . "user_roles:{$user->id}", $this->cacheTtl, function () use ($user) {
            return $user->getActiveRoles();
        });
    }

    public function getUserPermissions($user)
    {
        return Cache::remember($this->cachePrefix . "user_permissions:{$user->id}", $this->cacheTtl, function () use ($user) {
            return $user->getAllPermissions();
        });
    }

    public function canUserAccessRole($user, $targetRole)
    {
        $userRole = $user->getHighestRole();
        if (!$userRole || !$targetRole) {
            return false;
        }
        
        return $userRole->level >= $targetRole->level;
    }

    public function canUserManageRole($user, $targetRole)
    {
        $userRole = $user->getHighestRole();
        if (!$userRole || !$targetRole) {
            return false;
        }
        
        return $userRole->level > $targetRole->level;
    }

    public function getUsersWithRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        if (!$role) {
            return collect();
        }

        return $role->users;
    }

    public function getRoleUsersCount($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        return $role ? $role->getUsersCount() : 0;
    }

    public function getRolePermissionsCount($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        return $role ? $role->getPermissionsCount() : 0;
    }

    public function isSystemRole($role)
    {
        $systemRoles = ['super_admin', 'admin', 'user', 'guest'];
        return in_array($role->name, $systemRoles);
    }

    public function hasUsers($role)
    {
        return $this->getRoleUsersCount($role) > 0;
    }

    public function userHasRole($user, $roleName)
    {
        return $user->hasRole($roleName);
    }

    public function userHasPermission($user, $permissionName)
    {
        return $user->hasPermissionTo($permissionName);
    }

    public function userHasAnyRole($user, $roles)
    {
        return $user->hasAnyRole($roles);
    }

    public function userHasAnyPermission($user, $permissions)
    {
        return $user->hasAnyPermission($permissions);
    }

    public function userHasAllPermissions($user, $permissions)
    {
        return $user->hasAllPermissions($permissions);
    }

    public function getRolePermissions($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }

        return $role ? $role->permissions : collect();
    }

    public function getPermissionRoles($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::where('name', $permission)->first();
        }

        return $permission ? $permission->roles : collect();
    }

    public function createDefaultRoles()
    {
        $defaultRoles = Role::getDefaultRoles();
        
        foreach ($defaultRoles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        $this->clearRoleCache();
        return true;
    }

    public function createDefaultPermissions()
    {
        $defaultPermissions = Permission::getDefaultPermissions();
        
        foreach ($defaultPermissions as $permissionData) {
            Permission::firstOrCreate(
                ['name' => $permissionData['name']],
                array_merge($permissionData, ['is_system' => true])
            );
        }

        $this->clearRoleCache();
        return true;
    }

    public function assignDefaultPermissionsToRoles()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        foreach ($roles as $role) {
            $rolePermissions = $this->getDefaultPermissionsForRole($role->name);
            $role->syncPermissions($rolePermissions);
        }

        $this->clearRoleCache();
        return true;
    }

    protected function getDefaultPermissionsForRole($roleName)
    {
        $permissionMap = [
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

        return $permissionMap[$roleName] ?? [];
    }

    public function cleanupExpiredRoles()
    {
        $expiredRoles = Role::getExpiredRoles();
        $count = 0;

        foreach ($expiredRoles as $role) {
            if (!$this->isSystemRole($role) && !$this->hasUsers($role)) {
                $role->delete();
                $count++;
            }
        }

        $this->clearRoleCache();
        return $count;
    }

    public function getRoleAnalytics($days = 30)
    {
        return [
            'role_usage' => $this->getRoleUsageStats($days),
            'permission_usage' => $this->getPermissionUsageStats($days),
            'user_role_distribution' => $this->getUserRoleDistribution(),
            'role_creation_trends' => $this->getRoleCreationTrends($days),
        ];
    }

    protected function getRoleUsageStats($days)
    {
        return DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.created_at', '>=', now()->subDays($days))
            ->selectRaw('roles.name, COUNT(*) as usage_count')
            ->groupBy('roles.name')
            ->orderBy('usage_count', 'desc')
            ->get();
    }

    protected function getPermissionUsageStats($days)
    {
        return DB::table('model_has_permissions')
            ->join('permissions', 'model_has_permissions.permission_id', '=', 'permissions.id')
            ->where('model_has_permissions.created_at', '>=', now()->subDays($days))
            ->selectRaw('permissions.name, COUNT(*) as usage_count')
            ->groupBy('permissions.name')
            ->orderBy('usage_count', 'desc')
            ->get();
    }

    protected function getUserRoleDistribution()
    {
        return DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->selectRaw('roles.name, COUNT(*) as user_count')
            ->groupBy('roles.name')
            ->orderBy('user_count', 'desc')
            ->get();
    }

    protected function getRoleCreationTrends($days)
    {
        return Role::where('created_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    protected function clearRoleCache()
    {
        Cache::forget($this->cachePrefix . 'hierarchy');
        Cache::forget($this->cachePrefix . 'active');
        Cache::forget($this->cachePrefix . 'stats');
    }

    protected function clearUserCache($user)
    {
        Cache::forget($this->cachePrefix . "user_roles:{$user->id}");
        Cache::forget($this->cachePrefix . "user_permissions:{$user->id}");
    }
}
