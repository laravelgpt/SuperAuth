<?php

namespace SuperAuth\Core;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

class ConfigurationManager
{
    protected $cachePrefix = 'superauth:config:';
    protected $cacheTtl = 3600; // 1 hour
    protected $configPath = 'config/features';

    /**
     * Get configuration for a specific feature
     */
    public function getFeatureConfig(string $feature): array
    {
        $cacheKey = $this->cachePrefix . "feature:{$feature}";
        
        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($feature) {
            $configFile = "{$this->configPath}/{$feature}.php";
            
            if (File::exists($configFile)) {
                return include $configFile;
            }
            
            return [];
        });
    }

    /**
     * Get all feature configurations
     */
    public function getAllFeatureConfigs(): array
    {
        $features = ['authentication', 'authorization', 'security', 'notifications', 'ai'];
        $configs = [];
        
        foreach ($features as $feature) {
            $configs[$feature] = $this->getFeatureConfig($feature);
        }
        
        return $configs;
    }

    /**
     * Update feature configuration
     */
    public function updateFeatureConfig(string $feature, array $config): bool
    {
        $configFile = "{$this->configPath}/{$feature}.php";
        
        // Backup current config
        if (File::exists($configFile)) {
            File::copy($configFile, "{$configFile}.backup");
        }
        
        // Generate new config file
        $content = $this->generateConfigFile($config);
        File::put($configFile, $content);
        
        // Clear cache
        $this->clearFeatureConfigCache($feature);
        
        return true;
    }

    /**
     * Get configuration value
     */
    public function get(string $feature, string $key, $default = null)
    {
        $config = $this->getFeatureConfig($feature);
        return data_get($config, $key, $default);
    }

    /**
     * Set configuration value
     */
    public function set(string $feature, string $key, $value): bool
    {
        $config = $this->getFeatureConfig($feature);
        data_set($config, $key, $value);
        
        return $this->updateFeatureConfig($feature, $config);
    }

    /**
     * Get configuration schema
     */
    public function getConfigSchema(string $feature): array
    {
        $schemas = [
            'authentication' => [
                'enabled' => ['type' => 'boolean', 'default' => true, 'description' => 'Enable authentication feature'],
                'methods.email_password.enabled' => ['type' => 'boolean', 'default' => true, 'description' => 'Enable email/password authentication'],
                'methods.otp.enabled' => ['type' => 'boolean', 'default' => true, 'description' => 'Enable OTP authentication'],
                'methods.social.enabled' => ['type' => 'boolean', 'default' => true, 'description' => 'Enable social authentication'],
                'security.password_breach_check.enabled' => ['type' => 'boolean', 'default' => true, 'description' => 'Enable password breach checking'],
                'security.password_strength.enabled' => ['type' => 'boolean', 'default' => true, 'description' => 'Enable password strength analysis'],
            ],
            'authorization' => [
                'enabled' => ['type' => 'boolean', 'default' => true, 'description' => 'Enable authorization feature'],
                'roles.enabled' => ['type' => 'boolean', 'default' => true, 'description' => 'Enable role management'],
                'permissions.enabled' => ['type' => 'boolean', 'default' => true, 'description' => 'Enable permission management'],
                'access_control.enabled' => ['type' => 'boolean', 'default' => true, 'description' => 'Enable access control'],
            ],
            'security' => [
                'enabled' => ['type' => 'boolean', 'default' => true, 'description' => 'Enable security feature'],
                'password.breach_check.enabled' => ['type' => 'boolean', 'default' => true, 'description' => 'Enable password breach checking'],
                'password.strength.enabled' => ['type' => 'boolean', 'default' => true, 'description' => 'Enable password strength analysis'],
                'headers.enabled' => ['type' => 'boolean', 'default' => true, 'description' => 'Enable security headers'],
                'rate_limiting.enabled' => ['type' => 'boolean', 'default' => true, 'description' => 'Enable rate limiting'],
            ],
            'notifications' => [
                'enabled' => ['type' => 'boolean', 'default' => true, 'description' => 'Enable notifications feature'],
                'channels.email.enabled' => ['type' => 'boolean', 'default' => true, 'description' => 'Enable email notifications'],
                'channels.sms.enabled' => ['type' => 'boolean', 'default' => false, 'description' => 'Enable SMS notifications'],
                'channels.telegram.enabled' => ['type' => 'boolean', 'default' => false, 'description' => 'Enable Telegram notifications'],
                'channels.slack.enabled' => ['type' => 'boolean', 'default' => false, 'description' => 'Enable Slack notifications'],
                'channels.whatsapp.enabled' => ['type' => 'boolean', 'default' => false, 'description' => 'Enable WhatsApp notifications'],
            ],
            'ai' => [
                'enabled' => ['type' => 'boolean', 'default' => true, 'description' => 'Enable AI features'],
                'anomaly_detection.enabled' => ['type' => 'boolean', 'default' => true, 'description' => 'Enable anomaly detection'],
                'risk_scoring.enabled' => ['type' => 'boolean', 'default' => true, 'description' => 'Enable risk scoring'],
                'recommendations.enabled' => ['type' => 'boolean', 'default' => true, 'description' => 'Enable AI recommendations'],
            ],
        ];
        
        return $schemas[$feature] ?? [];
    }

    /**
     * Validate configuration
     */
    public function validateConfig(string $feature, array $config): array
    {
        $schema = $this->getConfigSchema($feature);
        $errors = [];
        
        foreach ($schema as $key => $rules) {
            $value = data_get($config, $key);
            
            if (isset($rules['required']) && $rules['required'] && $value === null) {
                $errors[] = "Field '{$key}' is required";
            }
            
            if ($value !== null && isset($rules['type'])) {
                $type = $rules['type'];
                $valid = match($type) {
                    'boolean' => is_bool($value),
                    'string' => is_string($value),
                    'integer' => is_int($value),
                    'array' => is_array($value),
                    'numeric' => is_numeric($value),
                    default => true
                };
                
                if (!$valid) {
                    $errors[] = "Field '{$key}' must be of type {$type}";
                }
            }
        }
        
        return $errors;
    }

    /**
     * Get configuration recommendations
     */
    public function getConfigRecommendations(string $feature): array
    {
        $config = $this->getFeatureConfig($feature);
        $recommendations = [];
        
        // Authentication recommendations
        if ($feature === 'authentication') {
            if (!$this->get($feature, 'security.password_breach_check.enabled')) {
                $recommendations[] = [
                    'type' => 'security',
                    'message' => 'Enable password breach checking for better security',
                    'action' => 'Enable password breach checking',
                ];
            }
            
            if (!$this->get($feature, 'security.password_strength.enabled')) {
                $recommendations[] = [
                    'type' => 'security',
                    'message' => 'Enable password strength analysis for better security',
                    'action' => 'Enable password strength analysis',
                ];
            }
        }
        
        // Security recommendations
        if ($feature === 'security') {
            if (!$this->get($feature, 'headers.enabled')) {
                $recommendations[] = [
                    'type' => 'security',
                    'message' => 'Enable security headers for better protection',
                    'action' => 'Enable security headers',
                ];
            }
            
            if (!$this->get($feature, 'rate_limiting.enabled')) {
                $recommendations[] = [
                    'type' => 'security',
                    'message' => 'Enable rate limiting to prevent abuse',
                    'action' => 'Enable rate limiting',
                ];
            }
        }
        
        // Notifications recommendations
        if ($feature === 'notifications') {
            if (!$this->get($feature, 'channels.email.enabled')) {
                $recommendations[] = [
                    'type' => 'functionality',
                    'message' => 'Enable email notifications for user communication',
                    'action' => 'Enable email notifications',
                ];
            }
        }
        
        return $recommendations;
    }

    /**
     * Export configuration
     */
    public function exportConfig(string $feature): string
    {
        $config = $this->getFeatureConfig($feature);
        return json_encode($config, JSON_PRETTY_PRINT);
    }

    /**
     * Import configuration
     */
    public function importConfig(string $feature, string $jsonConfig): bool
    {
        $config = json_decode($jsonConfig, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return false;
        }
        
        return $this->updateFeatureConfig($feature, $config);
    }

    /**
     * Reset configuration to defaults
     */
    public function resetConfig(string $feature): bool
    {
        $defaultConfig = $this->getDefaultConfig($feature);
        return $this->updateFeatureConfig($feature, $defaultConfig);
    }

    /**
     * Get default configuration
     */
    protected function getDefaultConfig(string $feature): array
    {
        $defaults = [
            'authentication' => [
                'enabled' => true,
                'methods' => [
                    'email_password' => ['enabled' => true],
                    'otp' => ['enabled' => true],
                    'social' => ['enabled' => true],
                ],
                'security' => [
                    'password_breach_check' => ['enabled' => true],
                    'password_strength' => ['enabled' => true],
                ],
            ],
            'authorization' => [
                'enabled' => true,
                'roles' => ['enabled' => true],
                'permissions' => ['enabled' => true],
                'access_control' => ['enabled' => true],
            ],
            'security' => [
                'enabled' => true,
                'password' => [
                    'breach_check' => ['enabled' => true],
                    'strength' => ['enabled' => true],
                ],
                'headers' => ['enabled' => true],
                'rate_limiting' => ['enabled' => true],
            ],
            'notifications' => [
                'enabled' => true,
                'channels' => [
                    'email' => ['enabled' => true],
                    'sms' => ['enabled' => false],
                    'telegram' => ['enabled' => false],
                    'slack' => ['enabled' => false],
                    'whatsapp' => ['enabled' => false],
                ],
            ],
            'ai' => [
                'enabled' => true,
                'anomaly_detection' => ['enabled' => true],
                'risk_scoring' => ['enabled' => true],
                'recommendations' => ['enabled' => true],
            ],
        ];
        
        return $defaults[$feature] ?? [];
    }

    /**
     * Generate configuration file content
     */
    protected function generateConfigFile(array $config): string
    {
        $content = "<?php\n\nreturn [\n";
        $content .= $this->arrayToPhp($config, 1);
        $content .= "];\n";
        
        return $content;
    }

    /**
     * Convert array to PHP code
     */
    protected function arrayToPhp(array $array, int $indent = 0): string
    {
        $content = '';
        $spaces = str_repeat('    ', $indent);
        
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $content .= "{$spaces}'{$key}' => [\n";
                $content .= $this->arrayToPhp($value, $indent + 1);
                $content .= "{$spaces}],\n";
            } else {
                $value = is_string($value) ? "'{$value}'" : (is_bool($value) ? ($value ? 'true' : 'false') : $value);
                $content .= "{$spaces}'{$key}' => {$value},\n";
            }
        }
        
        return $content;
    }

    /**
     * Clear feature configuration cache
     */
    protected function clearFeatureConfigCache(string $feature): void
    {
        $cacheKey = $this->cachePrefix . "feature:{$feature}";
        Cache::forget($cacheKey);
    }
}
