<?php

namespace SuperAuth\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class EnhancedBreachCheckService
{
    protected $apiUrl = 'https://api.pwnedpasswords.com/range/';
    protected $cacheTtl = 3600; // 1 hour
    protected $timeout = 10; // seconds

    public function __construct()
    {
        $this->apiUrl = config('superauth.breach_check.api_url', $this->apiUrl);
        $this->cacheTtl = config('superauth.breach_check.cache_ttl', $this->cacheTtl);
        $this->timeout = config('superauth.breach_check.timeout', $this->timeout);
    }

    public function checkPassword($password)
    {
        $passwordHash = hash('sha1', $password);
        $prefix = strtoupper(substr($passwordHash, 0, 5));
        $suffix = strtoupper(substr($passwordHash, 5));
        
        $cacheKey = "breach_check:{$prefix}";
        
        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($prefix, $suffix) {
            return $this->performBreachCheck($prefix, $suffix);
        });
    }

    protected function performBreachCheck($prefix, $suffix)
    {
        try {
            $startTime = microtime(true);
            
            $response = Http::timeout($this->timeout)
                ->get($this->apiUrl . $prefix);
            
            $responseTime = round((microtime(true) - $startTime) * 1000);
            
            if ($response->successful()) {
                $breachCount = $this->parseBreachResponse($response->body(), $suffix);
                
                return [
                    'breach_count' => $breachCount,
                    'is_breached' => $breachCount > 0,
                    'risk_level' => $this->determineRiskLevel($breachCount),
                    'confidence' => $this->calculateConfidence($breachCount),
                    'security_score' => $this->calculateSecurityScore($breachCount),
                    'api_response_time' => $responseTime,
                    'cached' => false,
                    'timestamp' => now()->toISOString(),
                ];
            } else {
                throw new \Exception('API request failed: ' . $response->status());
            }
            
        } catch (\Exception $e) {
            Log::warning('Breach check failed', [
                'error' => $e->getMessage(),
                'prefix' => $prefix,
            ]);
            
            return [
                'breach_count' => 0,
                'is_breached' => false,
                'risk_level' => 'unknown',
                'confidence' => 0,
                'security_score' => 0,
                'api_response_time' => 0,
                'cached' => false,
                'error' => $e->getMessage(),
                'timestamp' => now()->toISOString(),
            ];
        }
    }

    protected function parseBreachResponse($responseBody, $suffix)
    {
        $lines = explode("\r\n", $responseBody);
        
        foreach ($lines as $line) {
            if (empty($line)) continue;
            
            $parts = explode(':', $line);
            if (count($parts) === 2 && $parts[0] === $suffix) {
                return (int) $parts[1];
            }
        }
        
        return 0;
    }

    protected function determineRiskLevel($breachCount)
    {
        if ($breachCount >= 1000) return 'critical';
        if ($breachCount >= 100) return 'high';
        if ($breachCount >= 10) return 'medium';
        if ($breachCount >= 1) return 'low';
        return 'none';
    }

    protected function calculateConfidence($breachCount)
    {
        if ($breachCount === 0) return 100;
        if ($breachCount >= 1000) return 95;
        if ($breachCount >= 100) return 90;
        if ($breachCount >= 10) return 85;
        return 80;
    }

    protected function calculateSecurityScore($breachCount)
    {
        if ($breachCount === 0) return 100;
        if ($breachCount >= 1000) return 0;
        if ($breachCount >= 100) return 20;
        if ($breachCount >= 10) return 40;
        if ($breachCount >= 1) return 60;
        return 80;
    }

    public function checkMultiplePasswords($passwords)
    {
        $results = [];
        
        foreach ($passwords as $password) {
            $results[] = $this->checkPassword($password);
        }
        
        return $results;
    }

    public function getBreachStats()
    {
        $cacheKey = 'breach_stats';
        
        return Cache::remember($cacheKey, $this->cacheTtl, function () {
            return [
                'total_checks' => $this->getTotalChecks(),
                'breached_passwords' => $this->getBreachedPasswordsCount(),
                'safe_passwords' => $this->getSafePasswordsCount(),
                'average_breach_count' => $this->getAverageBreachCount(),
                'top_breach_counts' => $this->getTopBreachCounts(),
            ];
        });
    }

    public function getBreachTrends($days = 30)
    {
        $cacheKey = "breach_trends:{$days}";
        
        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($days) {
            return $this->calculateBreachTrends($days);
        });
    }

    public function getRiskDistribution()
    {
        $cacheKey = 'risk_distribution';
        
        return Cache::remember($cacheKey, $this->cacheTtl, function () {
            return [
                'none' => $this->getRiskCount('none'),
                'low' => $this->getRiskCount('low'),
                'medium' => $this->getRiskCount('medium'),
                'high' => $this->getRiskCount('high'),
                'critical' => $this->getRiskCount('critical'),
            ];
        });
    }

    public function getSecurityRecommendations($breachCount)
    {
        $recommendations = [];
        
        if ($breachCount > 0) {
            $recommendations[] = 'This password has been found in data breaches';
            $recommendations[] = 'Consider using a different, more secure password';
            $recommendations[] = 'Use a password manager to generate unique passwords';
            $recommendations[] = 'Enable two-factor authentication for additional security';
        } else {
            $recommendations[] = 'This password appears to be secure';
            $recommendations[] = 'Continue using strong, unique passwords';
            $recommendations[] = 'Regularly update your passwords';
        }
        
        return $recommendations;
    }

    public function getBreachAnalysis($breachCount)
    {
        return [
            'breach_count' => $breachCount,
            'risk_level' => $this->determineRiskLevel($breachCount),
            'confidence' => $this->calculateConfidence($breachCount),
            'security_score' => $this->calculateSecurityScore($breachCount),
            'recommendations' => $this->getSecurityRecommendations($breachCount),
            'status' => $breachCount > 0 ? 'compromised' : 'secure',
            'severity' => $this->getSeverityLevel($breachCount),
        ];
    }

    protected function getSeverityLevel($breachCount)
    {
        if ($breachCount >= 1000) return 'critical';
        if ($breachCount >= 100) return 'high';
        if ($breachCount >= 10) return 'medium';
        if ($breachCount >= 1) return 'low';
        return 'none';
    }

    public function getBreachHistory($password)
    {
        $passwordHash = hash('sha1', $password);
        $cacheKey = "breach_history:{$passwordHash}";
        
        return Cache::get($cacheKey, []);
    }

    public function recordBreachCheck($password, $result)
    {
        $passwordHash = hash('sha1', $password);
        $cacheKey = "breach_history:{$passwordHash}";
        
        $history = Cache::get($cacheKey, []);
        $history[] = [
            'timestamp' => now()->toISOString(),
            'breach_count' => $result['breach_count'],
            'risk_level' => $result['risk_level'],
            'security_score' => $result['security_score'],
        ];
        
        // Keep only last 10 checks
        if (count($history) > 10) {
            $history = array_slice($history, -10);
        }
        
        Cache::put($cacheKey, $history, $this->cacheTtl);
    }

    public function getApiStatus()
    {
        try {
            $response = Http::timeout(5)->get($this->apiUrl . '00000');
            return [
                'status' => 'online',
                'response_time' => $response->transferStats->getHandlerStat('total_time'),
                'last_check' => now()->toISOString(),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'offline',
                'error' => $e->getMessage(),
                'last_check' => now()->toISOString(),
            ];
        }
    }

    public function clearCache()
    {
        Cache::forget('breach_stats');
        Cache::forget('risk_distribution');
        Cache::forget('breach_trends:30');
    }

    protected function getTotalChecks()
    {
        // This would typically come from a database or cache
        return Cache::get('breach_check_total', 0);
    }

    protected function getBreachedPasswordsCount()
    {
        return Cache::get('breach_check_breached', 0);
    }

    protected function getSafePasswordsCount()
    {
        return Cache::get('breach_check_safe', 0);
    }

    protected function getAverageBreachCount()
    {
        return Cache::get('breach_check_average', 0);
    }

    protected function getTopBreachCounts()
    {
        return Cache::get('breach_check_top', []);
    }

    protected function getRiskCount($riskLevel)
    {
        return Cache::get("breach_check_risk_{$riskLevel}", 0);
    }

    protected function calculateBreachTrends($days)
    {
        // This would typically come from a database
        return [
            'total_checks' => 0,
            'breached_passwords' => 0,
            'safe_passwords' => 0,
            'average_breach_count' => 0,
        ];
    }

    public function getConfiguration()
    {
        return [
            'api_url' => $this->apiUrl,
            'cache_ttl' => $this->cacheTtl,
            'timeout' => $this->timeout,
        ];
    }

    public function updateConfiguration($config)
    {
        $this->apiUrl = $config['api_url'] ?? $this->apiUrl;
        $this->cacheTtl = $config['cache_ttl'] ?? $this->cacheTtl;
        $this->timeout = $config['timeout'] ?? $this->timeout;
        
        return true;
    }
}
