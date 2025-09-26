<?php

namespace SuperAuth\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use SuperAuth\Models\User;

class SecureLoggingService
{
    protected $channel;
    protected $context;
    protected $sensitiveFields;

    public function __construct()
    {
        $this->channel = config('superauth.logging.channel', 'daily');
        $this->context = [];
        $this->sensitiveFields = [
            'password',
            'password_confirmation',
            'current_password',
            'new_password',
            'token',
            'secret',
            'key',
            'api_key',
            'access_token',
            'refresh_token',
        ];
    }

    public function logInfo($message, $context = [], $user = null)
    {
        $this->setContext($context, $user);
        $this->sanitizeContext();
        
        Log::channel($this->channel)->info($message, $this->context);
        $this->cacheLogEntry('info', $message);
    }

    public function logWarning($message, $context = [], $user = null)
    {
        $this->setContext($context, $user);
        $this->sanitizeContext();
        
        Log::channel($this->channel)->warning($message, $this->context);
        $this->cacheLogEntry('warning', $message);
    }

    public function logError($message, $context = [], $user = null)
    {
        $this->setContext($context, $user);
        $this->sanitizeContext();
        
        Log::channel($this->channel)->error($message, $this->context);
        $this->cacheLogEntry('error', $message);
    }

    public function logCritical($message, $context = [], $user = null)
    {
        $this->setContext($context, $user);
        $this->sanitizeContext();
        
        Log::channel($this->channel)->critical($message, $this->context);
        $this->cacheLogEntry('critical', $message);
    }

    public function logSecurity($message, $context = [], $user = null)
    {
        $this->setContext($context, $user);
        $this->sanitizeContext();
        
        Log::channel('security')->info($message, $this->context);
        $this->cacheLogEntry('security', $message);
    }

    public function logAuthentication($message, $context = [], $user = null)
    {
        $this->setContext($context, $user);
        $this->sanitizeContext();
        
        Log::channel('auth')->info($message, $this->context);
        $this->cacheLogEntry('auth', $message);
    }

    public function logAuthorization($message, $context = [], $user = null)
    {
        $this->setContext($context, $user);
        $this->sanitizeContext();
        
        Log::channel('auth')->warning($message, $this->context);
        $this->cacheLogEntry('auth', $message);
    }

    public function logPasswordChange($user, $context = [])
    {
        $this->setContext($context, $user);
        $this->sanitizeContext();
        
        Log::channel('security')->info('Password changed', $this->context);
        $this->cacheLogEntry('security', 'Password changed');
    }

    public function logPasswordBreach($user, $breachCount, $context = [])
    {
        $this->setContext($context, $user);
        $this->sanitizeContext();
        
        Log::channel('security')->warning("Password breach detected: {$breachCount} breaches", $this->context);
        $this->cacheLogEntry('security', 'Password breach detected');
    }

    public function logLoginAttempt($user, $success, $context = [])
    {
        $this->setContext($context, $user);
        $this->sanitizeContext();
        
        $level = $success ? 'info' : 'warning';
        $message = $success ? 'Login successful' : 'Login failed';
        
        Log::channel('auth')->$level($message, $this->context);
        $this->cacheLogEntry('auth', $message);
    }

    public function logRoleChange($user, $oldRole, $newRole, $context = [])
    {
        $this->setContext($context, $user);
        $this->sanitizeContext();
        
        Log::channel('security')->info("Role changed from {$oldRole} to {$newRole}", $this->context);
        $this->cacheLogEntry('security', 'Role changed');
    }

    public function logPermissionChange($user, $permission, $granted, $context = [])
    {
        $this->setContext($context, $user);
        $this->sanitizeContext();
        
        $action = $granted ? 'granted' : 'revoked';
        Log::channel('security')->info("Permission {$permission} {$action}", $this->context);
        $this->cacheLogEntry('security', "Permission {$action}");
    }

    public function logUnusualActivity($user, $activity, $context = [])
    {
        $this->setContext($context, $user);
        $this->sanitizeContext();
        
        Log::channel('security')->warning("Unusual activity detected: {$activity}", $this->context);
        $this->cacheLogEntry('security', 'Unusual activity detected');
    }

    public function logApiAccess($endpoint, $method, $user = null, $context = [])
    {
        $this->setContext($context, $user);
        $this->sanitizeContext();
        
        Log::channel('api')->info("API access: {$method} {$endpoint}", $this->context);
        $this->cacheLogEntry('api', 'API access');
    }

    public function logDataExport($user, $dataType, $context = [])
    {
        $this->setContext($context, $user);
        $this->sanitizeContext();
        
        Log::channel('security')->info("Data export: {$dataType}", $this->context);
        $this->cacheLogEntry('security', 'Data export');
    }

    public function logDataDeletion($user, $dataType, $context = [])
    {
        $this->setContext($context, $user);
        $this->sanitizeContext();
        
        Log::channel('security')->warning("Data deletion: {$dataType}", $this->context);
        $this->cacheLogEntry('security', 'Data deletion');
    }

    protected function setContext($context, $user = null)
    {
        $this->context = array_merge($this->context, $context);
        
        if ($user) {
            $this->context['user_id'] = $user->id;
            $this->context['user_email'] = $user->email;
            $this->context['user_name'] = $user->name;
        }
        
        $this->context['ip_address'] = request()->ip();
        $this->context['user_agent'] = request()->userAgent();
        $this->context['timestamp'] = now()->toISOString();
        $this->context['request_id'] = request()->header('X-Request-ID', uniqid());
    }

    protected function sanitizeContext()
    {
        foreach ($this->sensitiveFields as $field) {
            if (isset($this->context[$field])) {
                $this->context[$field] = '[REDACTED]';
            }
        }
        
        // Remove sensitive data from nested arrays
        $this->context = $this->recursiveSanitize($this->context);
    }

    protected function recursiveSanitize($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (in_array($key, $this->sensitiveFields)) {
                    $data[$key] = '[REDACTED]';
                } elseif (is_array($value)) {
                    $data[$key] = $this->recursiveSanitize($value);
                }
            }
        }
        
        return $data;
    }

    protected function cacheLogEntry($level, $message)
    {
        $key = 'log_entries:' . now()->format('Y-m-d');
        $entry = [
            'level' => $level,
            'message' => $message,
            'context' => $this->context,
            'timestamp' => now()->toISOString(),
        ];
        
        $entries = Cache::get($key, []);
        $entries[] = $entry;
        
        // Keep only last 100 entries per day
        if (count($entries) > 100) {
            $entries = array_slice($entries, -100);
        }
        
        Cache::put($key, $entries, now()->endOfDay());
    }

    public function getLogEntries($date = null, $level = null, $limit = 50)
    {
        $date = $date ?: now()->format('Y-m-d');
        $key = 'log_entries:' . $date;
        $entries = Cache::get($key, []);
        
        if ($level) {
            $entries = array_filter($entries, function($entry) use ($level) {
                return $entry['level'] === $level;
            });
        }
        
        return array_slice($entries, -$limit);
    }

    public function getLogStats($days = 7)
    {
        $stats = [];
        
        for ($i = 0; $i < $days; $i++) {
            $date = now()->subDays($i)->format('Y-m-d');
            $key = 'log_entries:' . $date;
            $entries = Cache::get($key, []);
            
            $stats[$date] = [
                'total' => count($entries),
                'info' => count(array_filter($entries, fn($e) => $e['level'] === 'info')),
                'warning' => count(array_filter($entries, fn($e) => $e['level'] === 'warning')),
                'error' => count(array_filter($entries, fn($e) => $e['level'] === 'error')),
                'critical' => count(array_filter($entries, fn($e) => $e['level'] === 'critical')),
                'security' => count(array_filter($entries, fn($e) => $e['level'] === 'security')),
                'auth' => count(array_filter($entries, fn($e) => $e['level'] === 'auth')),
            ];
        }
        
        return $stats;
    }

    public function clearLogCache($date = null)
    {
        $date = $date ?: now()->format('Y-m-d');
        $key = 'log_entries:' . $date;
        Cache::forget($key);
    }

    public function exportLogs($date = null, $level = null)
    {
        $entries = $this->getLogEntries($date, $level, 1000);
        
        $export = [
            'export_date' => now()->toISOString(),
            'date_range' => $date ?: now()->format('Y-m-d'),
            'level_filter' => $level,
            'total_entries' => count($entries),
            'entries' => $entries,
        ];
        
        return $export;
    }
}
