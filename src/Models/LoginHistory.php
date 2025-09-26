<?php

namespace SuperAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SuperAuth\Models\User;

class LoginHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'location',
        'device',
        'browser',
        'os',
        'login_at',
        'logout_at',
        'success',
        'failure_reason',
        'is_unusual',
        'is_high_risk_ip',
        'risk_score',
        'anomaly_score',
        'confidence_score',
        'ai_analysis',
        'security_notes',
    ];

    protected $casts = [
        'login_at' => 'datetime',
        'logout_at' => 'datetime',
        'success' => 'boolean',
        'is_unusual' => 'boolean',
        'is_high_risk_ip' => 'boolean',
        'risk_score' => 'integer',
        'anomaly_score' => 'integer',
        'confidence_score' => 'integer',
        'ai_analysis' => 'array',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeSuccessful($query)
    {
        return $query->where('success', true);
    }

    public function scopeFailed($query)
    {
        return $query->where('success', false);
    }

    public function scopeUnusual($query)
    {
        return $query->where('is_unusual', true);
    }

    public function scopeHighRisk($query)
    {
        return $query->where('is_high_risk_ip', true);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByIp($query, $ip)
    {
        return $query->where('ip_address', $ip);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('login_at', '>=', now()->subDays($days));
    }

    public function scopeByDevice($query, $device)
    {
        return $query->where('device', $device);
    }

    public function scopeByBrowser($query, $browser)
    {
        return $query->where('browser', $browser);
    }

    public function scopeByLocation($query, $location)
    {
        return $query->where('location', $location);
    }

    public function scopeHighRiskScore($query, $score = 70)
    {
        return $query->where('risk_score', '>=', $score);
    }

    public function scopeHighAnomalyScore($query, $score = 80)
    {
        return $query->where('anomaly_score', '>=', $score);
    }

    // Methods
    public function getStatus()
    {
        if ($this->success) {
            return 'successful';
        } else {
            return 'failed';
        }
    }

    public function getStatusColor()
    {
        return $this->success ? 'green' : 'red';
    }

    public function getStatusIcon()
    {
        return $this->success ? 'check-circle' : 'times-circle';
    }

    public function getStatusText()
    {
        return $this->success ? 'Successful' : 'Failed';
    }

    public function getRiskLevel()
    {
        if ($this->risk_score >= 90) {
            return 'critical';
        } elseif ($this->risk_score >= 70) {
            return 'high';
        } elseif ($this->risk_score >= 50) {
            return 'medium';
        } else {
            return 'low';
        }
    }

    public function getRiskColor()
    {
        return match($this->getRiskLevel()) {
            'critical' => 'red',
            'high' => 'orange',
            'medium' => 'yellow',
            'low' => 'green',
            default => 'gray'
        };
    }

    public function getRiskIcon()
    {
        return match($this->getRiskLevel()) {
            'critical' => 'exclamation-triangle',
            'high' => 'exclamation-circle',
            'medium' => 'exclamation',
            'low' => 'check-circle',
            default => 'question-circle'
        };
    }

    public function getAnomalyLevel()
    {
        if ($this->anomaly_score >= 90) {
            return 'critical';
        } elseif ($this->anomaly_score >= 70) {
            return 'high';
        } elseif ($this->anomaly_score >= 50) {
            return 'medium';
        } else {
            return 'low';
        }
    }

    public function getAnomalyColor()
    {
        return match($this->getAnomalyLevel()) {
            'critical' => 'red',
            'high' => 'orange',
            'medium' => 'yellow',
            'low' => 'green',
            default => 'gray'
        };
    }

    public function getConfidenceLevel()
    {
        if ($this->confidence_score >= 90) {
            return 'very_high';
        } elseif ($this->confidence_score >= 70) {
            return 'high';
        } elseif ($this->confidence_score >= 50) {
            return 'medium';
        } else {
            return 'low';
        }
    }

    public function getConfidenceColor()
    {
        return match($this->getConfidenceLevel()) {
            'very_high' => 'green',
            'high' => 'blue',
            'medium' => 'yellow',
            'low' => 'red',
            default => 'gray'
        };
    }

    public function getSessionDuration()
    {
        if (!$this->logout_at) {
            return null;
        }
        
        return $this->login_at->diffInMinutes($this->logout_at);
    }

    public function getSessionDurationText()
    {
        $duration = $this->getSessionDuration();
        
        if (!$duration) {
            return 'Active';
        }
        
        if ($duration < 60) {
            return "{$duration} minutes";
        } else {
            $hours = floor($duration / 60);
            $minutes = $duration % 60;
            return "{$hours}h {$minutes}m";
        }
    }

    public function getDeviceInfo()
    {
        return [
            'device' => $this->device,
            'browser' => $this->browser,
            'os' => $this->os,
            'user_agent' => $this->user_agent,
        ];
    }

    public function getLocationInfo()
    {
        return [
            'location' => $this->location,
            'ip_address' => $this->ip_address,
        ];
    }

    public function getSecurityInfo()
    {
        return [
            'risk_score' => $this->risk_score,
            'anomaly_score' => $this->anomaly_score,
            'confidence_score' => $this->confidence_score,
            'is_unusual' => $this->is_unusual,
            'is_high_risk_ip' => $this->is_high_risk_ip,
            'security_notes' => $this->security_notes,
        ];
    }

    public function getAiAnalysis()
    {
        return $this->ai_analysis ?? [];
    }

    public function getFailureReason()
    {
        return $this->failure_reason ?? 'Unknown';
    }

    public function isActive()
    {
        return is_null($this->logout_at);
    }

    public function isExpired()
    {
        return $this->login_at->isBefore(now()->subDays(30));
    }

    public function getDaysSinceLogin()
    {
        return $this->login_at->diffInDays(now());
    }

    public function getHoursSinceLogin()
    {
        return $this->login_at->diffInHours(now());
    }

    public function getMinutesSinceLogin()
    {
        return $this->login_at->diffInMinutes(now());
    }

    // Static Methods
    public static function getLoginStats()
    {
        return [
            'total' => self::count(),
            'successful' => self::successful()->count(),
            'failed' => self::failed()->count(),
            'unusual' => self::unusual()->count(),
            'high_risk' => self::highRisk()->count(),
            'recent' => self::recent()->count(),
        ];
    }

    public static function getLoginTrends($days = 30)
    {
        return self::where('login_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(login_at) as date, COUNT(*) as count, SUM(success) as successful_count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public static function getTopIps($limit = 10)
    {
        return self::selectRaw('ip_address, COUNT(*) as count, SUM(success) as successful_count')
            ->groupBy('ip_address')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getTopDevices($limit = 10)
    {
        return self::selectRaw('device, COUNT(*) as count, SUM(success) as successful_count')
            ->groupBy('device')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getTopBrowsers($limit = 10)
    {
        return self::selectRaw('browser, COUNT(*) as count, SUM(success) as successful_count')
            ->groupBy('browser')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getTopLocations($limit = 10)
    {
        return self::selectRaw('location, COUNT(*) as count, SUM(success) as successful_count')
            ->groupBy('location')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getUnusualLogins($days = 7)
    {
        return self::unusual()
            ->where('login_at', '>=', now()->subDays($days))
            ->with('user')
            ->orderBy('login_at', 'desc')
            ->get();
    }

    public static function getHighRiskLogins($days = 7)
    {
        return self::highRisk()
            ->where('login_at', '>=', now()->subDays($days))
            ->with('user')
            ->orderBy('login_at', 'desc')
            ->get();
    }

    public static function getRecentLogins($userId, $limit = 10)
    {
        return self::byUser($userId)
            ->orderBy('login_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getLoginSummary($userId)
    {
        $userLogins = self::byUser($userId);
        
        return [
            'total' => $userLogins->count(),
            'successful' => $userLogins->successful()->count(),
            'failed' => $userLogins->failed()->count(),
            'unusual' => $userLogins->unusual()->count(),
            'high_risk' => $userLogins->highRisk()->count(),
            'recent' => $userLogins->recent()->count(),
            'last_login' => $userLogins->orderBy('login_at', 'desc')->first(),
        ];
    }

    public static function cleanupOldLogins($days = 90)
    {
        return self::where('login_at', '<', now()->subDays($days))->delete();
    }

    public static function getActiveSessions()
    {
        return self::whereNull('logout_at')
            ->where('login_at', '>=', now()->subDays(1))
            ->with('user')
            ->get();
    }

    public static function getFailedLoginAttempts($userId, $hours = 1)
    {
        return self::byUser($userId)
            ->failed()
            ->where('login_at', '>=', now()->subHours($hours))
            ->count();
    }

    public static function isIpBlocked($ip, $maxAttempts = 5, $hours = 1)
    {
        return self::byIp($ip)
            ->failed()
            ->where('login_at', '>=', now()->subHours($hours))
            ->count() >= $maxAttempts;
    }
}
