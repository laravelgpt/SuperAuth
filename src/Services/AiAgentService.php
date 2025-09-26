<?php

namespace SuperAuth\Services;

use SuperAuth\Models\User;
use SuperAuth\Models\LoginHistory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AiAgentService
{
    /**
     * Log user login with AI analysis
     */
    public function logLogin(User $user, array $loginData)
    {
        try {
            // Create login history record
            $loginHistory = LoginHistory::create([
                'user_id' => $user->id,
                'ip_address' => $loginData['ip_address'] ?? request()->ip(),
                'user_agent' => $loginData['user_agent'] ?? request()->userAgent(),
                'country' => $loginData['country'] ?? null,
                'city' => $loginData['city'] ?? null,
                'latitude' => $loginData['latitude'] ?? null,
                'longitude' => $loginData['longitude'] ?? null,
                'device_type' => $this->detectDeviceType($loginData['user_agent'] ?? ''),
                'browser' => $this->detectBrowser($loginData['user_agent'] ?? ''),
                'os' => $this->detectOS($loginData['user_agent'] ?? ''),
                'is_successful' => $loginData['is_successful'] ?? true,
                'failure_reason' => $loginData['failure_reason'] ?? null,
                'metadata' => $loginData['metadata'] ?? [],
            ]);

            // Perform AI analysis
            $this->analyzeLoginPattern($user, $loginHistory);
            $this->checkForAnomalies($user, $loginHistory);
            $this->updateRiskScore($user, $loginHistory);

            return $loginHistory;
        } catch (\Exception $e) {
            Log::error('AI Agent login logging failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Analyze login patterns
     */
    protected function analyzeLoginPattern(User $user, LoginHistory $loginHistory)
    {
        $recentLogins = LoginHistory::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays(30))
            ->orderBy('created_at', 'desc')
            ->get();

        // Check for unusual login times
        $this->checkUnusualLoginTime($user, $loginHistory, $recentLogins);
        
        // Check for unusual locations
        if ($loginHistory->country) {
            $this->checkUnusualCountry($user, $loginHistory->country, $recentLogins);
        }
        
        // Check for unusual devices
        $this->checkUnusualDevice($user, $loginHistory, $recentLogins);
    }

    /**
     * Check for anomalies
     */
    protected function checkForAnomalies(User $user, LoginHistory $loginHistory)
    {
        $anomalies = [];

        // Check for rapid login attempts
        $recentAttempts = LoginHistory::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->count();

        if ($recentAttempts > 5) {
            $anomalies[] = [
                'type' => 'rapid_login_attempts',
                'severity' => 'high',
                'message' => 'Multiple login attempts detected',
                'data' => ['attempts' => $recentAttempts]
            ];
        }

        // Check for unusual IP patterns
        $ipCount = LoginHistory::where('user_id', $user->id)
            ->where('ip_address', $loginHistory->ip_address)
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        if ($ipCount === 0 && $loginHistory->ip_address !== '127.0.0.1') {
            $anomalies[] = [
                'type' => 'new_ip_address',
                'severity' => 'medium',
                'message' => 'Login from new IP address',
                'data' => ['ip' => $loginHistory->ip_address]
            ];
        }

        // Store anomalies
        if (!empty($anomalies)) {
            $this->storeAnomalies($user, $anomalies);
        }
    }

    /**
     * Check unusual login time
     */
    protected function checkUnusualLoginTime(User $user, LoginHistory $loginHistory, $recentLogins)
    {
        $loginHour = $loginHistory->created_at->hour;
        $userLoginHours = $recentLogins->pluck('created_at')->map(function ($date) {
            return $date->hour;
        })->toArray();

        if (!empty($userLoginHours)) {
            $avgHour = array_sum($userLoginHours) / count($userLoginHours);
            $deviation = abs($loginHour - $avgHour);

            if ($deviation > 6) { // More than 6 hours deviation
                $this->createSecurityAlert($user, 'unusual_login_time', [
                    'current_hour' => $loginHour,
                    'average_hour' => round($avgHour),
                    'deviation' => $deviation
                ]);
            }
        }
    }

    /**
     * Check unusual country
     */
    protected function checkUnusualCountry(User $user, string $country, $recentLogins)
    {
        $userCountries = $recentLogins->pluck('country')->filter()->unique()->toArray();
        
        if (!in_array($country, $userCountries) && !empty($userCountries)) {
            $this->createSecurityAlert($user, 'unusual_country', [
                'current_country' => $country,
                'previous_countries' => $userCountries
            ]);
        }
    }

    /**
     * Check unusual device
     */
    protected function checkUnusualDevice(User $user, LoginHistory $loginHistory, $recentLogins)
    {
        $userDevices = $recentLogins->pluck('device_type')->filter()->unique()->toArray();
        
        if (!in_array($loginHistory->device_type, $userDevices) && !empty($userDevices)) {
            $this->createSecurityAlert($user, 'unusual_device', [
                'current_device' => $loginHistory->device_type,
                'previous_devices' => $userDevices
            ]);
        }
    }

    /**
     * Update user risk score
     */
    protected function updateRiskScore(User $user, LoginHistory $loginHistory)
    {
        $riskScore = 0;
        
        // Base risk factors
        $recentLogins = LoginHistory::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subDays(7))
            ->get();

        // IP diversity risk
        $uniqueIPs = $recentLogins->pluck('ip_address')->unique()->count();
        if ($uniqueIPs > 3) {
            $riskScore += 20;
        }

        // Country diversity risk
        $uniqueCountries = $recentLogins->pluck('country')->filter()->unique()->count();
        if ($uniqueCountries > 2) {
            $riskScore += 30;
        }

        // Device diversity risk
        $uniqueDevices = $recentLogins->pluck('device_type')->filter()->unique()->count();
        if ($uniqueDevices > 2) {
            $riskScore += 15;
        }

        // Store risk score
        $user->update(['risk_score' => min($riskScore, 100)]);
    }

    /**
     * Generate AI insights
     */
    public function generateInsights()
    {
        $insights = [];

        // User growth insights
        $userGrowth = User::where('created_at', '>=', now()->subDays(30))->count();
        $insights[] = [
            'type' => 'user_growth',
            'title' => 'User Growth',
            'value' => $userGrowth,
            'trend' => $userGrowth > 0 ? 'up' : 'stable',
            'message' => "{$userGrowth} new users in the last 30 days"
        ];

        // Security insights
        $securityAlerts = $this->getSecurityAlerts();
        $insights[] = [
            'type' => 'security_alerts',
            'title' => 'Security Alerts',
            'value' => count($securityAlerts),
            'trend' => count($securityAlerts) > 0 ? 'up' : 'stable',
            'message' => count($securityAlerts) . ' security alerts detected'
        ];

        // Login patterns
        $loginPatterns = $this->analyzeLoginPatterns();
        $insights[] = [
            'type' => 'login_patterns',
            'title' => 'Login Patterns',
            'value' => $loginPatterns['anomalies'],
            'trend' => $loginPatterns['anomalies'] > 0 ? 'up' : 'stable',
            'message' => $loginPatterns['anomalies'] . ' unusual login patterns detected'
        ];

        return $insights;
    }

    /**
     * Get security alerts
     */
    public function getSecurityAlerts()
    {
        return Cache::remember('security_alerts', 300, function () {
            return LoginHistory::where('created_at', '>=', now()->subDays(7))
                ->where('is_successful', false)
                ->count();
        });
    }

    /**
     * Analyze login patterns
     */
    protected function analyzeLoginPatterns()
    {
        $recentLogins = LoginHistory::where('created_at', '>=', now()->subDays(7))->get();
        
        $anomalies = 0;
        foreach ($recentLogins as $login) {
            // Simple anomaly detection logic
            if ($login->country && $login->country !== 'US') {
                $anomalies++;
            }
        }

        return [
            'total_logins' => $recentLogins->count(),
            'anomalies' => $anomalies,
            'anomaly_rate' => $recentLogins->count() > 0 ? ($anomalies / $recentLogins->count()) * 100 : 0
        ];
    }

    /**
     * Create security alert
     */
    protected function createSecurityAlert(User $user, string $type, array $data)
    {
        Log::warning("Security alert for user {$user->id}: {$type}", $data);
        
        // Store in cache for dashboard
        $alerts = Cache::get('security_alerts', []);
        $alerts[] = [
            'user_id' => $user->id,
            'type' => $type,
            'data' => $data,
            'timestamp' => now()
        ];
        Cache::put('security_alerts', $alerts, 3600);
    }

    /**
     * Store anomalies
     */
    protected function storeAnomalies(User $user, array $anomalies)
    {
        foreach ($anomalies as $anomaly) {
            Log::warning("Anomaly detected for user {$user->id}: {$anomaly['type']}", $anomaly);
        }
    }

    /**
     * Detect device type
     */
    protected function detectDeviceType(string $userAgent)
    {
        if (preg_match('/Mobile|Android|iPhone|iPad/', $userAgent)) {
            return 'mobile';
        } elseif (preg_match('/Tablet|iPad/', $userAgent)) {
            return 'tablet';
        }
        return 'desktop';
    }

    /**
     * Detect browser
     */
    protected function detectBrowser(string $userAgent)
    {
        if (preg_match('/Chrome/', $userAgent)) return 'Chrome';
        if (preg_match('/Firefox/', $userAgent)) return 'Firefox';
        if (preg_match('/Safari/', $userAgent)) return 'Safari';
        if (preg_match('/Edge/', $userAgent)) return 'Edge';
        return 'Unknown';
    }

    /**
     * Detect OS
     */
    protected function detectOS(string $userAgent)
    {
        if (preg_match('/Windows/', $userAgent)) return 'Windows';
        if (preg_match('/Mac/', $userAgent)) return 'macOS';
        if (preg_match('/Linux/', $userAgent)) return 'Linux';
        if (preg_match('/Android/', $userAgent)) return 'Android';
        if (preg_match('/iOS/', $userAgent)) return 'iOS';
        return 'Unknown';
    }

    /**
     * Get anomalies
     */
    public function getAnomalies()
    {
        return Cache::get('security_alerts', []);
    }

    /**
     * Get recommendations
     */
    public function getRecommendations()
    {
        $recommendations = [];
        
        $highRiskUsers = User::where('risk_score', '>', 70)->count();
        if ($highRiskUsers > 0) {
            $recommendations[] = [
                'type' => 'high_risk_users',
                'title' => 'High Risk Users',
                'message' => "{$highRiskUsers} users have high risk scores. Consider additional security measures.",
                'priority' => 'high'
            ];
        }

        $recentAlerts = count($this->getSecurityAlerts());
        if ($recentAlerts > 10) {
            $recommendations[] = [
                'type' => 'security_alerts',
                'title' => 'Security Alerts',
                'message' => "{$recentAlerts} security alerts in the last 7 days. Review security policies.",
                'priority' => 'medium'
            ];
        }

        return $recommendations;
    }

    /**
     * Get security threats
     */
    public function getSecurityThreats()
    {
        return LoginHistory::where('created_at', '>=', now()->subDays(7))
            ->where('is_successful', false)
            ->with('user')
            ->get();
    }

    /**
     * Get security incidents
     */
    public function getSecurityIncidents()
    {
        return Cache::get('security_alerts', []);
    }
}
