<?php

namespace SuperAuth\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class BreachCheckService
{
    protected $apiUrl = 'https://api.pwnedpasswords.com/range/';
    protected $cacheTimeout = 3600; // 1 hour

    /**
     * Check if password has been breached (alias for checkPasswordBreach)
     */
    public function check(string $password): int
    {
        return $this->checkPasswordBreach($password);
    }

    /**
     * Check if password has been breached
     */
    public function checkPasswordBreach(string $password): int
    {
        $hash = strtoupper(sha1($password));
        $prefix = substr($hash, 0, 5);
        $suffix = substr($hash, 5);

        $cacheKey = "breach_check_{$prefix}";

        return Cache::remember($cacheKey, $this->cacheTimeout, function () use ($prefix, $suffix) {
            try {
                $response = Http::timeout(10)->get($this->apiUrl . $prefix);
                
                if ($response->successful()) {
                    $hashes = explode("\r\n", $response->body());
                    
                    foreach ($hashes as $line) {
                        if (strpos($line, $suffix . ':') === 0) {
                            $count = (int) substr($line, strpos($line, ':') + 1);
                            return $count;
                        }
                    }
                }
                
                return 0;
            } catch (\Exception $e) {
                Log::warning('Password breach check failed: ' . $e->getMessage());
                return 0;
            }
        });
    }

    /**
     * Check multiple passwords
     */
    public function checkMultiplePasswords(array $passwords): array
    {
        $results = [];
        
        foreach ($passwords as $password) {
            $results[$password] = $this->checkPasswordBreach($password);
        }
        
        return $results;
    }

    /**
     * Get breach statistics
     */
    public function getBreachStats(): array
    {
        return Cache::remember('breach_stats', 86400, function () {
            try {
                $response = Http::timeout(10)->get('https://api.pwnedpasswords.com/breaches');
                
                if ($response->successful()) {
                    $breaches = $response->json();
                    return [
                        'total_breaches' => count($breaches),
                        'total_accounts' => array_sum(array_column($breaches, 'PwnCount')),
                        'last_updated' => now()
                    ];
                }
                
                return [
                    'total_breaches' => 0,
                    'total_accounts' => 0,
                    'last_updated' => now()
                ];
            } catch (\Exception $e) {
                Log::warning('Breach stats fetch failed: ' . $e->getMessage());
                return [
                    'total_breaches' => 0,
                    'total_accounts' => 0,
                    'last_updated' => now()
                ];
            }
        });
    }
}
