<?php

namespace SuperAuth\Core;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class FeatureManager
{
    protected $cachePrefix = 'superauth:features:';
    protected $cacheTtl = 3600; // 1 hour

    /**
     * Check if a feature is enabled
     */
    public function isEnabled(string $feature): bool
    {
        $cacheKey = $this->cachePrefix . $feature;
        
        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($feature) {
            return $this->getFeatureConfig($feature, 'enabled', false);
        });
    }

    /**
     * Enable a feature
     */
    public function enable(string $feature): bool
    {
        $this->setFeatureConfig($feature, 'enabled', true);
        $this->clearFeatureCache($feature);
        return true;
    }

    /**
     * Disable a feature
     */
    public function disable(string $feature): bool
    {
        $this->setFeatureConfig($feature, 'enabled', false);
        $this->clearFeatureCache($feature);
        return true;
    }

    /**
     * Get feature configuration
     */
    public function getConfig(string $feature, string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->getFeatureConfig($feature);
        }
        
        return $this->getFeatureConfig($feature, $key, $default);
    }

    /**
     * Set feature configuration
     */
    public function setConfig(string $feature, string $key, $value): bool
    {
        $this->setFeatureConfig($feature, $key, $value);
        $this->clearFeatureCache($feature);
        return true;
    }

    /**
     * Get all enabled features
     */
    public function getEnabledFeatures(): array
    {
        $features = $this->getAllFeatures();
        $enabled = [];
        
        foreach ($features as $feature => $config) {
            if ($this->isEnabled($feature)) {
                $enabled[$feature] = $config;
            }
        }
        
        return $enabled;
    }

    /**
     * Get all disabled features
     */
    public function getDisabledFeatures(): array
    {
        $features = $this->getAllFeatures();
        $disabled = [];
        
        foreach ($features as $feature => $config) {
            if (!$this->isEnabled($feature)) {
                $disabled[$feature] = $config;
            }
        }
        
        return $disabled;
    }

    /**
     * Get all features (alias for getAllFeatures)
     */
    public function getFeatures(): array
    {
        return $this->getAllFeatures();
    }

    /**
     * Get all features
     */
    public function getAllFeatures(): array
    {
        return [
            'authentication' => [
                'name' => 'Authentication',
                'description' => 'User authentication features including login, register, OTP, and social login',
                'enabled' => $this->isEnabled('authentication'),
                'config' => $this->getFeatureConfig('authentication'),
            ],
            'authorization' => [
                'name' => 'Authorization',
                'description' => 'Role and permission management features',
                'enabled' => $this->isEnabled('authorization'),
                'config' => $this->getFeatureConfig('authorization'),
            ],
            'security' => [
                'name' => 'Security',
                'description' => 'Security features including password strength, breach checking, and monitoring',
                'enabled' => $this->isEnabled('security'),
                'config' => $this->getFeatureConfig('security'),
            ],
            'notifications' => [
                'name' => 'Notifications',
                'description' => 'Multi-channel notification system',
                'enabled' => $this->isEnabled('notifications'),
                'config' => $this->getFeatureConfig('notifications'),
            ],
            'ai' => [
                'name' => 'AI Features',
                'description' => 'AI-powered monitoring and recommendations',
                'enabled' => $this->isEnabled('ai'),
                'config' => $this->getFeatureConfig('ai'),
            ],
        ];
    }

    /**
     * Get feature statistics
     */
    public function getFeatureStats(): array
    {
        $features = $this->getAllFeatures();
        $total = count($features);
        $enabled = count($this->getEnabledFeatures());
        $disabled = $total - $enabled;
        
        return [
            'total' => $total,
            'enabled' => $enabled,
            'disabled' => $disabled,
            'enabled_percentage' => $total > 0 ? round(($enabled / $total) * 100, 2) : 0,
        ];
    }

    /**
     * Bulk enable features
     */
    public function enableFeatures(array $features): bool
    {
        foreach ($features as $feature) {
            $this->enable($feature);
        }
        return true;
    }

    /**
     * Bulk disable features
     */
    public function disableFeatures(array $features): bool
    {
        foreach ($features as $feature) {
            $this->disable($feature);
        }
        return true;
    }

    /**
     * Reset feature to default configuration
     */
    public function resetFeature(string $feature): bool
    {
        $this->clearFeatureCache($feature);
        return true;
    }

    /**
     * Clear all feature cache
     */
    public function clearAllCache(): bool
    {
        $features = array_keys($this->getAllFeatures());
        
        foreach ($features as $feature) {
            $this->clearFeatureCache($feature);
        }
        
        return true;
    }

    /**
     * Get feature configuration from config files
     */
    protected function getFeatureConfig(string $feature, string $key = null, $default = null)
    {
        $configKey = "superauth.features.{$feature}";
        
        if ($key === null) {
            return Config::get($configKey, $default);
        }
        
        return Config::get("{$configKey}.{$key}", $default);
    }

    /**
     * Set feature configuration
     */
    protected function setFeatureConfig(string $feature, string $key, $value): void
    {
        $configKey = "superauth.features.{$feature}.{$key}";
        Config::set($configKey, $value);
    }

    /**
     * Clear feature cache
     */
    protected function clearFeatureCache(string $feature): void
    {
        $cacheKey = $this->cachePrefix . $feature;
        Cache::forget($cacheKey);
    }

    /**
     * Get feature dependencies
     */
    public function getFeatureDependencies(string $feature): array
    {
        $dependencies = [
            'authentication' => [],
            'authorization' => ['authentication'],
            'security' => ['authentication'],
            'notifications' => ['authentication'],
            'ai' => ['authentication', 'security'],
        ];
        
        return $dependencies[$feature] ?? [];
    }

    /**
     * Check if feature dependencies are met
     */
    public function checkDependencies(string $feature): array
    {
        $dependencies = $this->getFeatureDependencies($feature);
        $missing = [];
        
        foreach ($dependencies as $dependency) {
            if (!$this->isEnabled($dependency)) {
                $missing[] = $dependency;
            }
        }
        
        return [
            'dependencies' => $dependencies,
            'missing' => $missing,
            'can_enable' => empty($missing),
        ];
    }

    /**
     * Get feature recommendations
     */
    public function getFeatureRecommendations(): array
    {
        $recommendations = [];
        $features = $this->getAllFeatures();
        
        foreach ($features as $feature => $config) {
            if (!$this->isEnabled($feature)) {
                $deps = $this->checkDependencies($feature);
                
                if ($deps['can_enable']) {
                    $recommendations[] = [
                        'feature' => $feature,
                        'name' => $config['name'],
                        'description' => $config['description'],
                        'reason' => 'Dependencies are met',
                    ];
                }
            }
        }
        
        return $recommendations;
    }
}
