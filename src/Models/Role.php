<?php

namespace SuperAuth\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use SuperAuth\Models\User;
use SuperAuth\Models\Permission;

class Role extends SpatieRole
{
    protected $fillable = [
        'name',
        'guard_name',
        'display_name',
        'description',
        'level',
        'is_active',
        'expires_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime',
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
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    public function scopeHigherThan($query, $level)
    {
        return $query->where('level', '>', $level);
    }

    public function scopeLowerThan($query, $level)
    {
        return $query->where('level', '<', $level);
    }

    // Methods
    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function isActive()
    {
        return $this->is_active && !$this->isExpired();
    }

    public function getDaysUntilExpiry()
    {
        if (!$this->expires_at) {
            return null;
        }
        
        return now()->diffInDays($this->expires_at, false);
    }

    public function getExpiryStatus()
    {
        if (!$this->expires_at) {
            return 'permanent';
        }
        
        $days = $this->getDaysUntilExpiry();
        
        if ($days < 0) {
            return 'expired';
        } elseif ($days <= 7) {
            return 'expiring_soon';
        } else {
            return 'active';
        }
    }

    public function getUsersCount()
    {
        return $this->users()->count();
    }

    public function getPermissionsCount()
    {
        return $this->permissions()->count();
    }

    public function hasPermission($permission)
    {
        return $this->hasPermissionTo($permission);
    }

    public function hasAnyPermission(...$permissions): bool
    {
        return $this->permissions()->whereIn('name', $permissions)->exists();
    }

    public function hasAllPermissions(...$permissions): bool
    {
        $rolePermissions = $this->permissions()->pluck('name')->toArray();
        
        return count(array_intersect($permissions, $rolePermissions)) === count($permissions);
    }

    public function canAccessRole($targetRole)
    {
        if (!$targetRole) {
            return false;
        }
        
        return $this->level >= $targetRole->level;
    }

    public function canManageRole($targetRole)
    {
        if (!$targetRole) {
            return false;
        }
        
        return $this->level > $targetRole->level;
    }

    public function getHierarchyLevel()
    {
        return $this->level ?? 0;
    }

    public function getDisplayName()
    {
        return $this->display_name ?? $this->name;
    }

    public function getDescription()
    {
        return $this->description ?? 'No description available';
    }

    public function getStatusBadge()
    {
        if (!$this->is_active) {
            return 'inactive';
        }
        
        if ($this->isExpired()) {
            return 'expired';
        }
        
        $expiryStatus = $this->getExpiryStatus();
        if ($expiryStatus === 'expiring_soon') {
            return 'expiring_soon';
        }
        
        return 'active';
    }

    public function getStatusColor()
    {
        $status = $this->getStatusBadge();
        
        return match($status) {
            'active' => 'green',
            'inactive' => 'gray',
            'expired' => 'red',
            'expiring_soon' => 'yellow',
            default => 'gray'
        };
    }

    public function getStatusText()
    {
        $status = $this->getStatusBadge();
        
        return match($status) {
            'active' => 'Active',
            'inactive' => 'Inactive',
            'expired' => 'Expired',
            'expiring_soon' => 'Expiring Soon',
            default => 'Unknown'
        };
    }

    // Static Methods
    public static function getDefaultRoles()
    {
        return [
            [
                'name' => 'super_admin',
                'display_name' => 'Super Administrator',
                'description' => 'Full system access with all permissions',
                'level' => 100,
                'is_active' => true,
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Administrative access to manage users and content',
                'level' => 80,
                'is_active' => true,
            ],
            [
                'name' => 'moderator',
                'display_name' => 'Moderator',
                'description' => 'Moderation access to manage content and users',
                'level' => 60,
                'is_active' => true,
            ],
            [
                'name' => 'user',
                'display_name' => 'User',
                'description' => 'Standard user access',
                'level' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'guest',
                'display_name' => 'Guest',
                'description' => 'Limited guest access',
                'level' => 10,
                'is_active' => true,
            ],
        ];
    }

    public static function getRoleHierarchy()
    {
        return self::orderBy('level', 'desc')->get();
    }

    public static function getActiveRoles()
    {
        return self::active()->notExpired()->get();
    }

    public static function getExpiredRoles()
    {
        return self::expired()->get();
    }

    public static function getExpiringRoles($days = 7)
    {
        return self::where('expires_at', '<=', now()->addDays($days))
            ->where('expires_at', '>', now())
            ->get();
    }

    public static function getRoleStats()
    {
        return [
            'total' => self::count(),
            'active' => self::active()->notExpired()->count(),
            'inactive' => self::where('is_active', false)->count(),
            'expired' => self::expired()->count(),
            'expiring_soon' => self::getExpiringRoles()->count(),
        ];
    }

    public static function createDefaultRoles()
    {
        $defaultRoles = self::getDefaultRoles();
        
        foreach ($defaultRoles as $roleData) {
            self::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }
    }

    public static function cleanupExpiredRoles()
    {
        return self::expired()->delete();
    }
}
