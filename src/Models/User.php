<?php

namespace SuperAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use SuperAuth\Models\SocialAccount;
use SuperAuth\Models\PasswordBreach;
use SuperAuth\Models\OtpVerification;
use SuperAuth\Models\LoginHistory;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Database\Factories\UserFactory::new();
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'date_of_birth',
        'bio',
        'city',
        'country',
        'avatar',
        'is_active',
        'email_verified_at',
        'last_login_at',
        'last_login_ip',
        'login_count',
        'failed_login_attempts',
        'locked_until',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'locked_until' => 'datetime',
        'date_of_birth' => 'date',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function passwordBreaches()
    {
        return $this->hasMany(PasswordBreach::class);
    }

    public function otpVerifications()
    {
        return $this->hasMany(OtpVerification::class);
    }

    public function loginHistories()
    {
        return $this->hasMany(LoginHistory::class);
    }

    // Role and Permission Methods
    public function hasRole($role)
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function hasAnyRole($roles)
    {
        return $this->roles()->whereIn('name', $roles)->exists();
    }

    public function hasPermission($permission)
    {
        return $this->hasPermissionTo($permission);
    }

    public function hasAnyPermission($permissions)
    {
        return $this->hasAnyPermission($permissions);
    }

    public function hasAllPermissions($permissions)
    {
        return $this->hasAllPermissions($permissions);
    }

    // Role Hierarchy Methods
    public function getRoleHierarchy()
    {
        $roles = $this->roles()->orderBy('level', 'desc')->get();
        return $roles;
    }

    public function getHighestRole()
    {
        return $this->roles()->orderBy('level', 'desc')->first();
    }

    public function canAccessRole($targetRole)
    {
        $userRole = $this->getHighestRole();
        if (!$userRole || !$targetRole) {
            return false;
        }
        
        return $userRole->level >= $targetRole->level;
    }

    // Role Expiration Methods
    public function hasExpiredRoles()
    {
        return $this->roles()->where('expires_at', '<', now())->exists();
    }

    public function getActiveRoles()
    {
        return $this->roles()->where(function($query) {
            $query->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
        })->get();
    }

    public function getExpiredRoles()
    {
        return $this->roles()->where('expires_at', '<', now())->get();
    }

    // Feature Access Methods
    public function hasFeatureAccess($feature)
    {
        return $this->roles()->whereHas('permissions', function($query) use ($feature) {
            $query->where('name', 'like', "feature.{$feature}%");
        })->exists();
    }

    public function getFeaturePermissions($feature)
    {
        return $this->getAllPermissions()->filter(function($permission) use ($feature) {
            return str_starts_with($permission->name, "feature.{$feature}");
        });
    }

    // Security Methods
    public function isLocked()
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    public function lockAccount($minutes = 30)
    {
        $this->update([
            'locked_until' => now()->addMinutes($minutes)
        ]);
    }

    public function unlockAccount()
    {
        $this->update([
            'locked_until' => null,
            'failed_login_attempts' => 0
        ]);
    }

    public function incrementFailedAttempts()
    {
        $this->increment('failed_login_attempts');
        
        if ($this->failed_login_attempts >= 5) {
            $this->lockAccount();
        }
    }

    public function resetFailedAttempts()
    {
        $this->update(['failed_login_attempts' => 0]);
    }

    // Login Tracking
    public function recordLogin($ip = null, $userAgent = null, $location = null)
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip,
            'login_count' => $this->login_count + 1,
            'failed_login_attempts' => 0
        ]);

        // Create login history record
        $this->loginHistories()->create([
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'location' => $location,
            'login_at' => now(),
            'success' => true
        ]);
    }

    // Social Account Methods
    public function getSocialAccount($provider)
    {
        return $this->socialAccounts()->where('provider', $provider)->first();
    }

    public function hasSocialAccount($provider)
    {
        return $this->socialAccounts()->where('provider', $provider)->exists();
    }

    public function connectSocialAccount($provider, $providerId, $providerEmail = null)
    {
        return $this->socialAccounts()->create([
            'provider' => $provider,
            'provider_id' => $providerId,
            'provider_email' => $providerEmail,
            'connected_at' => now()
        ]);
    }

    // Password Security Methods
    public function hasPasswordBreach()
    {
        return $this->passwordBreaches()->where('is_breached', true)->exists();
    }

    public function getPasswordBreachCount()
    {
        return $this->passwordBreaches()->where('is_breached', true)->count();
    }

    public function recordPasswordBreach($password, $breachCount = 0)
    {
        return $this->passwordBreaches()->create([
            'password_hash' => hash('sha256', $password),
            'breach_count' => $breachCount,
            'is_breached' => $breachCount > 0,
            'checked_at' => now()
        ]);
    }

    // OTP Methods
    public function generateOtp($purpose = 'verification', $expiresIn = 10)
    {
        $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        
        $this->otpVerifications()->create([
            'otp' => $otp,
            'purpose' => $purpose,
            'expires_at' => now()->addMinutes($expiresIn),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);

        return $otp;
    }

    public function verifyOtp($otp, $purpose = 'verification')
    {
        $otpRecord = $this->otpVerifications()
            ->where('otp', $otp)
            ->where('purpose', $purpose)
            ->where('expires_at', '>', now())
            ->where('used_at', null)
            ->first();

        if ($otpRecord) {
            $otpRecord->update(['used_at' => now()]);
            return true;
        }

        return false;
    }

    // Utility Methods
    public function getAvatarUrl()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }
        
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function getFullName()
    {
        return $this->name;
    }

    public function getDisplayName()
    {
        return $this->name;
    }

    public function getInitials()
    {
        $names = explode(' ', $this->name);
        $initials = '';
        
        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
        
        return substr($initials, 0, 2);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeLocked($query)
    {
        return $query->where('locked_until', '>', now());
    }

    public function scopeUnlocked($query)
    {
        return $query->where(function($q) {
            $q->whereNull('locked_until')
              ->orWhere('locked_until', '<=', now());
        });
    }

    public function scopeWithRole($query, $role)
    {
        return $query->whereHas('roles', function($q) use ($role) {
            $q->where('name', $role);
        });
    }

    public function scopeWithPermission($query, $permission)
    {
        return $query->whereHas('permissions', function($q) use ($permission) {
            $q->where('name', $permission);
        });
    }
}
