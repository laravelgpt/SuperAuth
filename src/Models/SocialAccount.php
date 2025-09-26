<?php

namespace SuperAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SuperAuth\Models\User;

class SocialAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
        'provider_email',
        'provider_name',
        'provider_avatar',
        'provider_data',
        'connected_at',
        'last_used_at',
        'is_active',
    ];

    protected $casts = [
        'connected_at' => 'datetime',
        'last_used_at' => 'datetime',
        'is_active' => 'boolean',
        'provider_data' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByProvider($query, $provider)
    {
        return $query->where('provider', $provider);
    }

    public function scopeRecentlyUsed($query, $days = 30)
    {
        return $query->where('last_used_at', '>=', now()->subDays($days));
    }

    // Methods
    public function getProviderDisplayName()
    {
        return ucfirst($this->provider);
    }

    public function getProviderIcon()
    {
        $icons = [
            'google' => 'https://developers.google.com/identity/images/g-logo.png',
            'facebook' => 'https://static.xx.fbcdn.net/rsrc.php/v3/yx/r/pyNVUg5EM0j.png',
            'github' => 'https://github.githubassets.com/images/modules/logos_page/GitHub-Mark.png',
            'apple' => 'https://developer.apple.com/design/human-interface-guidelines/sign-in-with-apple/images/sign-in-with-apple-button.png',
            'twitter' => 'https://abs.twimg.com/icons/apps/twitter-logo.png',
            'linkedin' => 'https://static-exp1.licdn.com/sc/h/1btl0855i7f8z9zn4gr0mx8l5',
        ];

        return $icons[$this->provider] ?? null;
    }

    public function getProviderColor()
    {
        $colors = [
            'google' => '#4285F4',
            'facebook' => '#1877F2',
            'github' => '#333333',
            'apple' => '#000000',
            'twitter' => '#1DA1F2',
            'linkedin' => '#0077B5',
        ];

        return $colors[$this->provider] ?? '#6B7280';
    }

    public function updateLastUsed()
    {
        $this->update(['last_used_at' => now()]);
    }

    public function deactivate()
    {
        $this->update(['is_active' => false]);
    }

    public function activate()
    {
        $this->update(['is_active' => true]);
    }

    public function getProviderData($key = null)
    {
        if ($key) {
            return $this->provider_data[$key] ?? null;
        }
        
        return $this->provider_data;
    }

    public function setProviderData($key, $value)
    {
        $data = $this->provider_data ?? [];
        $data[$key] = $value;
        $this->update(['provider_data' => $data]);
    }

    public function isExpired()
    {
        // Check if the social account connection is expired (e.g., OAuth token expired)
        if (!$this->provider_data) {
            return false;
        }

        $expiresAt = $this->provider_data['expires_at'] ?? null;
        if (!$expiresAt) {
            return false;
        }

        return now()->isAfter($expiresAt);
    }

    public function getDaysSinceConnected()
    {
        return $this->connected_at->diffInDays(now());
    }

    public function getDaysSinceLastUsed()
    {
        if (!$this->last_used_at) {
            return null;
        }
        
        return $this->last_used_at->diffInDays(now());
    }

    // Static Methods
    public static function getAvailableProviders()
    {
        return [
            'google' => 'Google',
            'facebook' => 'Facebook',
            'github' => 'GitHub',
            'apple' => 'Apple',
            'twitter' => 'Twitter',
            'linkedin' => 'LinkedIn',
        ];
    }

    public static function getProviderStats()
    {
        return self::selectRaw('provider, COUNT(*) as count')
            ->groupBy('provider')
            ->orderBy('count', 'desc')
            ->get();
    }

    public static function getRecentConnections($days = 7)
    {
        return self::where('connected_at', '>=', now()->subDays($days))
            ->with('user')
            ->orderBy('connected_at', 'desc')
            ->get();
    }

    public static function getInactiveAccounts($days = 90)
    {
        return self::where('last_used_at', '<', now()->subDays($days))
            ->orWhereNull('last_used_at')
            ->with('user')
            ->get();
    }
}
