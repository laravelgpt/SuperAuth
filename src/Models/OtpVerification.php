<?php

namespace SuperAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SuperAuth\Models\User;

class OtpVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'otp',
        'purpose',
        'expires_at',
        'used_at',
        'ip_address',
        'user_agent',
        'attempts',
        'is_verified',
        'verified_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'verified_at' => 'datetime',
        'is_verified' => 'boolean',
        'attempts' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now())
                    ->whereNull('used_at')
                    ->where('is_verified', false);
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    public function scopeUsed($query)
    {
        return $query->whereNotNull('used_at');
    }

    public function scopeUnused($query)
    {
        return $query->whereNull('used_at');
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeUnverified($query)
    {
        return $query->where('is_verified', false);
    }

    public function scopeByPurpose($query, $purpose)
    {
        return $query->where('purpose', $purpose);
    }

    public function scopeRecent($query, $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByIp($query, $ip)
    {
        return $query->where('ip_address', $ip);
    }

    // Methods
    public function isExpired()
    {
        return $this->expires_at->isPast();
    }

    public function isValid()
    {
        return !$this->isExpired() && !$this->isUsed() && !$this->isVerified();
    }

    public function isUsed()
    {
        return !is_null($this->used_at);
    }

    public function isVerified()
    {
        return $this->is_verified;
    }

    public function getStatus()
    {
        if ($this->isVerified()) {
            return 'verified';
        } elseif ($this->isUsed()) {
            return 'used';
        } elseif ($this->isExpired()) {
            return 'expired';
        } else {
            return 'pending';
        }
    }

    public function getStatusColor()
    {
        return match($this->getStatus()) {
            'verified' => 'green',
            'used' => 'blue',
            'expired' => 'red',
            'pending' => 'yellow',
            default => 'gray'
        };
    }

    public function getStatusIcon()
    {
        return match($this->getStatus()) {
            'verified' => 'check-circle',
            'used' => 'check',
            'expired' => 'times-circle',
            'pending' => 'clock',
            default => 'question-circle'
        };
    }

    public function getStatusText()
    {
        return match($this->getStatus()) {
            'verified' => 'Verified',
            'used' => 'Used',
            'expired' => 'Expired',
            'pending' => 'Pending',
            default => 'Unknown'
        };
    }

    public function getTimeRemaining()
    {
        if ($this->isExpired()) {
            return 0;
        }
        
        return now()->diffInMinutes($this->expires_at, false);
    }

    public function getTimeRemainingText()
    {
        $minutes = $this->getTimeRemaining();
        
        if ($minutes <= 0) {
            return 'Expired';
        } elseif ($minutes < 60) {
            return "{$minutes} minutes";
        } else {
            $hours = floor($minutes / 60);
            $remainingMinutes = $minutes % 60;
            return "{$hours}h {$remainingMinutes}m";
        }
    }

    public function getPurposeText()
    {
        return match($this->purpose) {
            'verification' => 'Email Verification',
            'login' => 'Login Verification',
            'password_reset' => 'Password Reset',
            'two_factor' => 'Two-Factor Authentication',
            'security' => 'Security Verification',
            default => ucfirst($this->purpose)
        };
    }

    public function getPurposeIcon()
    {
        return match($this->purpose) {
            'verification' => 'envelope',
            'login' => 'sign-in-alt',
            'password_reset' => 'key',
            'two_factor' => 'shield-alt',
            'security' => 'lock',
            default => 'question-circle'
        };
    }

    public function getPurposeColor()
    {
        return match($this->purpose) {
            'verification' => 'blue',
            'login' => 'green',
            'password_reset' => 'orange',
            'two_factor' => 'purple',
            'security' => 'red',
            default => 'gray'
        };
    }

    public function incrementAttempts()
    {
        $this->increment('attempts');
    }

    public function markAsUsed()
    {
        $this->update(['used_at' => now()]);
    }

    public function markAsVerified()
    {
        $this->update([
            'is_verified' => true,
            'verified_at' => now()
        ]);
    }

    public function getAttemptsRemaining()
    {
        $maxAttempts = config('superauth.otp.max_attempts', 3);
        return max(0, $maxAttempts - $this->attempts);
    }

    public function hasExceededMaxAttempts()
    {
        $maxAttempts = config('superauth.otp.max_attempts', 3);
        return $this->attempts >= $maxAttempts;
    }

    public function getDeviceInfo()
    {
        return [
            'ip_address' => $this->ip_address,
            'user_agent' => $this->user_agent,
            'created_at' => $this->created_at,
        ];
    }

    // Static Methods
    public static function generateOtp($length = 6)
    {
        return str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
    }

    public static function createOtp($userId, $purpose = 'verification', $expiresIn = 10)
    {
        $otp = self::generateOtp();
        
        return self::create([
            'user_id' => $userId,
            'otp' => $otp,
            'purpose' => $purpose,
            'expires_at' => now()->addMinutes($expiresIn),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'attempts' => 0,
            'is_verified' => false,
        ]);
    }

    public static function verifyOtp($otp, $userId, $purpose = 'verification')
    {
        $otpRecord = self::where('otp', $otp)
            ->where('user_id', $userId)
            ->where('purpose', $purpose)
            ->valid()
            ->first();

        if (!$otpRecord) {
            return false;
        }

        if ($otpRecord->hasExceededMaxAttempts()) {
            return false;
        }

        $otpRecord->markAsVerified();
        return true;
    }

    public static function getOtpStats()
    {
        return [
            'total' => self::count(),
            'valid' => self::valid()->count(),
            'expired' => self::expired()->count(),
            'used' => self::used()->count(),
            'verified' => self::verified()->count(),
            'recent' => self::recent()->count(),
        ];
    }

    public static function getOtpTrends($days = 30)
    {
        return self::where('created_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(is_verified) as verified_count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public static function getRecentOtps($userId, $limit = 5)
    {
        return self::byUser($userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function cleanupExpiredOtps()
    {
        return self::expired()->delete();
    }

    public static function getOtpByUser($userId, $purpose = 'verification')
    {
        return self::byUser($userId)
            ->byPurpose($purpose)
            ->valid()
            ->first();
    }

    public static function hasActiveOtp($userId, $purpose = 'verification')
    {
        return self::byUser($userId)
            ->byPurpose($purpose)
            ->valid()
            ->exists();
    }

    public static function getOtpSummary($userId)
    {
        $userOtps = self::byUser($userId);
        
        return [
            'total' => $userOtps->count(),
            'valid' => $userOtps->valid()->count(),
            'expired' => $userOtps->expired()->count(),
            'used' => $userOtps->used()->count(),
            'verified' => $userOtps->verified()->count(),
            'recent' => $userOtps->recent()->count(),
            'last_otp' => $userOtps->orderBy('created_at', 'desc')->first(),
        ];
    }
}
