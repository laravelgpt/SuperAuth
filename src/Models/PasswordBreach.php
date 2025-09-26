<?php

namespace SuperAuth\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SuperAuth\Models\User;

class PasswordBreach extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'password_hash',
        'breach_count',
        'is_breached',
        'checked_at',
        'api_response_time',
        'cache_status',
        'risk_level',
        'confidence_score',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
        'is_breached' => 'boolean',
        'breach_count' => 'integer',
        'api_response_time' => 'integer',
        'confidence_score' => 'float',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeBreached($query)
    {
        return $query->where('is_breached', true);
    }

    public function scopeSafe($query)
    {
        return $query->where('is_breached', false);
    }

    public function scopeHighRisk($query)
    {
        return $query->where('risk_level', 'high');
    }

    public function scopeMediumRisk($query)
    {
        return $query->where('risk_level', 'medium');
    }

    public function scopeLowRisk($query)
    {
        return $query->where('risk_level', 'low');
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('checked_at', '>=', now()->subDays($days));
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Methods
    public function getRiskLevel()
    {
        if ($this->breach_count >= 100) {
            return 'critical';
        } elseif ($this->breach_count >= 10) {
            return 'high';
        } elseif ($this->breach_count >= 1) {
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

    public function getStatusText()
    {
        if ($this->is_breached) {
            return "Found in {$this->breach_count} breaches";
        }
        
        return 'No breaches found';
    }

    public function getStatusColor()
    {
        return $this->is_breached ? 'red' : 'green';
    }

    public function getStatusIcon()
    {
        return $this->is_breached ? 'times-circle' : 'check-circle';
    }

    public function getDaysSinceCheck()
    {
        return $this->checked_at->diffInDays(now());
    }

    public function isRecent()
    {
        return $this->checked_at->isAfter(now()->subDays(7));
    }

    public function isStale()
    {
        return $this->checked_at->isBefore(now()->subDays(30));
    }

    public function getCacheStatus()
    {
        return $this->cache_status ?? 'unknown';
    }

    public function getApiResponseTime()
    {
        return $this->api_response_time ?? 0;
    }

    public function getConfidenceScore()
    {
        return $this->confidence_score ?? 0;
    }

    public function getConfidenceText()
    {
        $score = $this->getConfidenceScore();
        
        if ($score >= 90) {
            return 'Very High';
        } elseif ($score >= 70) {
            return 'High';
        } elseif ($score >= 50) {
            return 'Medium';
        } else {
            return 'Low';
        }
    }

    public function getConfidenceColor()
    {
        $score = $this->getConfidenceScore();
        
        if ($score >= 70) {
            return 'green';
        } elseif ($score >= 50) {
            return 'yellow';
        } else {
            return 'red';
        }
    }

    // Static Methods
    public static function getBreachStats()
    {
        return [
            'total' => self::count(),
            'breached' => self::breached()->count(),
            'safe' => self::safe()->count(),
            'high_risk' => self::highRisk()->count(),
            'medium_risk' => self::mediumRisk()->count(),
            'low_risk' => self::lowRisk()->count(),
            'recent' => self::recent()->count(),
        ];
    }

    public static function getBreachTrends($days = 30)
    {
        return self::where('checked_at', '>=', now()->subDays($days))
            ->selectRaw('DATE(checked_at) as date, COUNT(*) as count, SUM(is_breached) as breached_count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public static function getTopBreachedPasswords($limit = 10)
    {
        return self::breached()
            ->selectRaw('password_hash, COUNT(*) as usage_count, AVG(breach_count) as avg_breach_count')
            ->groupBy('password_hash')
            ->orderBy('avg_breach_count', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getUsersWithBreachedPasswords()
    {
        return self::breached()
            ->with('user')
            ->get()
            ->groupBy('user_id');
    }

    public static function getRecentBreaches($days = 7)
    {
        return self::breached()
            ->where('checked_at', '>=', now()->subDays($days))
            ->with('user')
            ->orderBy('checked_at', 'desc')
            ->get();
    }

    public static function getHighRiskUsers()
    {
        return self::highRisk()
            ->with('user')
            ->get()
            ->groupBy('user_id');
    }

    public static function cleanupOldRecords($days = 90)
    {
        return self::where('checked_at', '<', now()->subDays($days))->delete();
    }

    public static function getBreachCountByUser($userId)
    {
        return self::byUser($userId)->breached()->count();
    }

    public static function getLastBreachCheck($userId)
    {
        return self::byUser($userId)->orderBy('checked_at', 'desc')->first();
    }

    public static function hasRecentBreach($userId, $days = 7)
    {
        return self::byUser($userId)
            ->breached()
            ->where('checked_at', '>=', now()->subDays($days))
            ->exists();
    }

    public static function getBreachSummary($userId)
    {
        $userBreaches = self::byUser($userId);
        
        return [
            'total_checks' => $userBreaches->count(),
            'breached_count' => $userBreaches->breached()->count(),
            'safe_count' => $userBreaches->safe()->count(),
            'last_check' => $userBreaches->orderBy('checked_at', 'desc')->first(),
            'high_risk_count' => $userBreaches->highRisk()->count(),
            'has_recent_breach' => self::hasRecentBreach($userId),
        ];
    }
}
