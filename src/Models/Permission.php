<?php

namespace SuperAuth\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use SuperAuth\Models\Role;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'guard_name',
        'display_name',
        'description',
        'category',
        'is_system',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeSystem($query)
    {
        return $query->where('is_system', true);
    }

    public function scopeCustom($query)
    {
        return $query->where('is_system', false);
    }

    public function scopeFeature($query, $feature)
    {
        return $query->where('name', 'like', "feature.{$feature}%");
    }

    public function scopeModule($query, $module)
    {
        return $query->where('name', 'like', "{$module}.%");
    }

    // Methods
    public function getDisplayName()
    {
        return $this->display_name ?? $this->name;
    }

    public function getDescription()
    {
        return $this->description ?? 'No description available';
    }

    public function getCategory()
    {
        return $this->category ?? 'general';
    }

    public function getModule()
    {
        $parts = explode('.', $this->name);
        return $parts[0] ?? 'general';
    }

    public function getAction()
    {
        $parts = explode('.', $this->name);
        return end($parts);
    }

    public function isSystemPermission()
    {
        return $this->is_system;
    }

    public function isCustomPermission()
    {
        return !$this->is_system;
    }

    public function isFeaturePermission()
    {
        return str_starts_with($this->name, 'feature.');
    }

    public function getFeatureName()
    {
        if (!$this->isFeaturePermission()) {
            return null;
        }
        
        $parts = explode('.', $this->name);
        return $parts[1] ?? null;
    }

    public function getRolesCount()
    {
        return $this->roles()->count();
    }

    public function getUsersCount()
    {
        return $this->users()->count();
    }

    public function getPermissionLevel()
    {
        $name = $this->name;
        
        if (str_contains($name, 'create')) return 'create';
        if (str_contains($name, 'read')) return 'read';
        if (str_contains($name, 'update')) return 'update';
        if (str_contains($name, 'delete')) return 'delete';
        if (str_contains($name, 'manage')) return 'manage';
        
        return 'access';
    }

    public function getPermissionIcon()
    {
        $level = $this->getPermissionLevel();
        
        return match($level) {
            'create' => 'plus',
            'read' => 'eye',
            'update' => 'edit',
            'delete' => 'trash',
            'manage' => 'cog',
            default => 'key'
        };
    }

    public function getPermissionColor()
    {
        $level = $this->getPermissionLevel();
        
        return match($level) {
            'create' => 'green',
            'read' => 'blue',
            'update' => 'yellow',
            'delete' => 'red',
            'manage' => 'purple',
            default => 'gray'
        };
    }

    // Static Methods
    public static function getDefaultPermissions()
    {
        return [
            // User Management
            ['name' => 'users.create', 'display_name' => 'Create Users', 'description' => 'Create new users', 'category' => 'users'],
            ['name' => 'users.read', 'display_name' => 'View Users', 'description' => 'View user information', 'category' => 'users'],
            ['name' => 'users.update', 'display_name' => 'Update Users', 'description' => 'Update user information', 'category' => 'users'],
            ['name' => 'users.delete', 'display_name' => 'Delete Users', 'description' => 'Delete users', 'category' => 'users'],
            ['name' => 'users.manage', 'display_name' => 'Manage Users', 'description' => 'Full user management', 'category' => 'users'],
            
            // Role Management
            ['name' => 'roles.create', 'display_name' => 'Create Roles', 'description' => 'Create new roles', 'category' => 'roles'],
            ['name' => 'roles.read', 'display_name' => 'View Roles', 'description' => 'View role information', 'category' => 'roles'],
            ['name' => 'roles.update', 'display_name' => 'Update Roles', 'description' => 'Update role information', 'category' => 'roles'],
            ['name' => 'roles.delete', 'display_name' => 'Delete Roles', 'description' => 'Delete roles', 'category' => 'roles'],
            ['name' => 'roles.manage', 'display_name' => 'Manage Roles', 'description' => 'Full role management', 'category' => 'roles'],
            
            // Permission Management
            ['name' => 'permissions.create', 'display_name' => 'Create Permissions', 'description' => 'Create new permissions', 'category' => 'permissions'],
            ['name' => 'permissions.read', 'display_name' => 'View Permissions', 'description' => 'View permission information', 'category' => 'permissions'],
            ['name' => 'permissions.update', 'display_name' => 'Update Permissions', 'description' => 'Update permission information', 'category' => 'permissions'],
            ['name' => 'permissions.delete', 'display_name' => 'Delete Permissions', 'description' => 'Delete permissions', 'category' => 'permissions'],
            ['name' => 'permissions.manage', 'display_name' => 'Manage Permissions', 'description' => 'Full permission management', 'category' => 'permissions'],
            
            // Admin Dashboard
            ['name' => 'admin.dashboard', 'display_name' => 'Admin Dashboard', 'description' => 'Access admin dashboard', 'category' => 'admin'],
            ['name' => 'admin.settings', 'display_name' => 'Admin Settings', 'description' => 'Access admin settings', 'category' => 'admin'],
            ['name' => 'admin.reports', 'display_name' => 'Admin Reports', 'description' => 'Access admin reports', 'category' => 'admin'],
            ['name' => 'admin.logs', 'display_name' => 'Admin Logs', 'description' => 'Access admin logs', 'category' => 'admin'],
            
            // Security Features
            ['name' => 'security.breach_check', 'display_name' => 'Password Breach Check', 'description' => 'Check password breaches', 'category' => 'security'],
            ['name' => 'security.password_strength', 'display_name' => 'Password Strength', 'description' => 'Analyze password strength', 'category' => 'security'],
            ['name' => 'security.ai_monitoring', 'display_name' => 'AI Monitoring', 'description' => 'Access AI security monitoring', 'category' => 'security'],
            ['name' => 'security.notifications', 'display_name' => 'Security Notifications', 'description' => 'Manage security notifications', 'category' => 'security'],
        ];
    }

    public static function getPermissionsByCategory()
    {
        return self::orderBy('category')->orderBy('name')->get()->groupBy('category');
    }

    public static function getSystemPermissions()
    {
        return self::system()->get();
    }

    public static function getCustomPermissions()
    {
        return self::custom()->get();
    }

    public static function getFeaturePermissions()
    {
        return self::where('name', 'like', 'feature.%')->get();
    }

    public static function getPermissionStats()
    {
        return [
            'total' => self::count(),
            'system' => self::system()->count(),
            'custom' => self::custom()->count(),
            'feature' => self::getFeaturePermissions()->count(),
        ];
    }

    public static function createDefaultPermissions()
    {
        $defaultPermissions = self::getDefaultPermissions();
        
        foreach ($defaultPermissions as $permissionData) {
            self::firstOrCreate(
                ['name' => $permissionData['name']],
                array_merge($permissionData, ['is_system' => true])
            );
        }
    }

    public static function getCategories()
    {
        return self::distinct()->pluck('category')->filter()->sort()->values();
    }

    public static function getModules()
    {
        return self::get()->map(function($permission) {
            return $permission->getModule();
        })->unique()->sort()->values();
    }
}
