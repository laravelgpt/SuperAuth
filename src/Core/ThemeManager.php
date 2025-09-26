<?php

namespace SuperAuth\Core;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class ThemeManager
{
    protected $cachePrefix = 'superauth:theme:';
    protected $cacheTtl = 3600; // 1 hour

    /**
     * Get available themes
     */
    public function getAvailableThemes(): array
    {
        return [
            'glass-morphism' => [
                'name' => 'Glass Morphism',
                'description' => 'Modern glass morphism design with blur effects',
                'preview' => 'glass-morphism-preview.png',
                'colors' => [
                    'primary' => '#667eea',
                    'secondary' => '#764ba2',
                    'accent' => '#f093fb',
                    'background' => 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)',
                ],
                'features' => [
                    'blur_effects' => true,
                    'gradients' => true,
                    'animations' => true,
                    'dark_mode' => true,
                ],
            ],
            'minimal' => [
                'name' => 'Minimal',
                'description' => 'Clean and minimal design',
                'preview' => 'minimal-preview.png',
                'colors' => [
                    'primary' => '#3b82f6',
                    'secondary' => '#1e40af',
                    'accent' => '#06b6d4',
                    'background' => '#ffffff',
                ],
                'features' => [
                    'blur_effects' => false,
                    'gradients' => false,
                    'animations' => false,
                    'dark_mode' => true,
                ],
            ],
            'dark' => [
                'name' => 'Dark Theme',
                'description' => 'Dark theme with modern styling',
                'preview' => 'dark-preview.png',
                'colors' => [
                    'primary' => '#8b5cf6',
                    'secondary' => '#6366f1',
                    'accent' => '#ec4899',
                    'background' => '#1f2937',
                ],
                'features' => [
                    'blur_effects' => true,
                    'gradients' => true,
                    'animations' => true,
                    'dark_mode' => true,
                ],
            ],
            'colorful' => [
                'name' => 'Colorful',
                'description' => 'Vibrant and colorful design',
                'preview' => 'colorful-preview.png',
                'colors' => [
                    'primary' => '#f59e0b',
                    'secondary' => '#ef4444',
                    'accent' => '#10b981',
                    'background' => 'linear-gradient(45deg, #f59e0b, #ef4444, #10b981)',
                ],
                'features' => [
                    'blur_effects' => false,
                    'gradients' => true,
                    'animations' => true,
                    'dark_mode' => false,
                ],
            ],
            'corporate' => [
                'name' => 'Corporate',
                'description' => 'Professional corporate design',
                'preview' => 'corporate-preview.png',
                'colors' => [
                    'primary' => '#1e40af',
                    'secondary' => '#374151',
                    'accent' => '#059669',
                    'background' => '#f9fafb',
                ],
                'features' => [
                    'blur_effects' => false,
                    'gradients' => false,
                    'animations' => false,
                    'dark_mode' => false,
                ],
            ],
        ];
    }

    /**
     * Get current theme
     */
    public function getCurrentTheme(): string
    {
        return Config::get('superauth.ui.theme', 'glass-morphism');
    }

    /**
     * Set current theme
     */
    public function setCurrentTheme(string $theme): bool
    {
        if (!$this->isThemeAvailable($theme)) {
            return false;
        }
        
        Config::set('superauth.ui.theme', $theme);
        $this->clearThemeCache();
        
        return true;
    }

    /**
     * Get theme configuration
     */
    public function getThemeConfig(string $theme = null): array
    {
        $theme = $theme ?: $this->getCurrentTheme();
        $cacheKey = $this->cachePrefix . "config:{$theme}";
        
        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($theme) {
            $themes = $this->getAvailableThemes();
            return $themes[$theme] ?? [];
        });
    }

    /**
     * Check if theme is available
     */
    public function isThemeAvailable(string $theme): bool
    {
        $themes = $this->getAvailableThemes();
        return isset($themes[$theme]);
    }

    /**
     * Get theme colors
     */
    public function getThemeColors(string $theme = null): array
    {
        $config = $this->getThemeConfig($theme);
        return $config['colors'] ?? [];
    }

    /**
     * Get theme features
     */
    public function getThemeFeatures(string $theme = null): array
    {
        $config = $this->getThemeConfig($theme);
        return $config['features'] ?? [];
    }

    /**
     * Check if theme supports feature
     */
    public function supportsFeature(string $feature, string $theme = null): bool
    {
        $features = $this->getThemeFeatures($theme);
        return $features[$feature] ?? false;
    }

    /**
     * Get theme CSS variables
     */
    public function getThemeCssVariables(string $theme = null): string
    {
        $colors = $this->getThemeColors($theme);
        $css = '';
        
        foreach ($colors as $name => $value) {
            $css .= "--superauth-{$name}: {$value};\n";
        }
        
        return $css;
    }

    /**
     * Get theme CSS classes
     */
    public function getThemeCssClasses(string $theme = null): array
    {
        $theme = $theme ?: $this->getCurrentTheme();
        
        return [
            'container' => "superauth-theme-{$theme}",
            'primary' => "superauth-primary-{$theme}",
            'secondary' => "superauth-secondary-{$theme}",
            'accent' => "superauth-accent-{$theme}",
            'background' => "superauth-background-{$theme}",
        ];
    }

    /**
     * Get theme JavaScript configuration
     */
    public function getThemeJsConfig(string $theme = null): array
    {
        $config = $this->getThemeConfig($theme);
        
        return [
            'theme' => $theme ?: $this->getCurrentTheme(),
            'colors' => $config['colors'] ?? [],
            'features' => $config['features'] ?? [],
            'cssClasses' => $this->getThemeCssClasses($theme),
        ];
    }

    /**
     * Get theme recommendations
     */
    public function getThemeRecommendations(): array
    {
        $currentTheme = $this->getCurrentTheme();
        $themes = $this->getAvailableThemes();
        $recommendations = [];
        
        foreach ($themes as $theme => $config) {
            if ($theme !== $currentTheme) {
                $recommendations[] = [
                    'theme' => $theme,
                    'name' => $config['name'],
                    'description' => $config['description'],
                    'reason' => $this->getThemeRecommendationReason($theme, $currentTheme),
                ];
            }
        }
        
        return $recommendations;
    }

    /**
     * Get theme recommendation reason
     */
    protected function getThemeRecommendationReason(string $theme, string $currentTheme): string
    {
        $reasons = [
            'glass-morphism' => 'Modern and trendy design with blur effects',
            'minimal' => 'Clean and professional design',
            'dark' => 'Easy on the eyes for dark environments',
            'colorful' => 'Vibrant and engaging design',
            'corporate' => 'Professional and business-appropriate',
        ];
        
        return $reasons[$theme] ?? 'Alternative design option';
    }

    /**
     * Get theme statistics
     */
    public function getThemeStats(): array
    {
        $themes = $this->getAvailableThemes();
        $total = count($themes);
        $withBlurEffects = 0;
        $withGradients = 0;
        $withAnimations = 0;
        $withDarkMode = 0;
        
        foreach ($themes as $theme => $config) {
            $features = $config['features'] ?? [];
            
            if ($features['blur_effects'] ?? false) $withBlurEffects++;
            if ($features['gradients'] ?? false) $withGradients++;
            if ($features['animations'] ?? false) $withAnimations++;
            if ($features['dark_mode'] ?? false) $withDarkMode++;
        }
        
        return [
            'total' => $total,
            'with_blur_effects' => $withBlurEffects,
            'with_gradients' => $withGradients,
            'with_animations' => $withAnimations,
            'with_dark_mode' => $withDarkMode,
        ];
    }

    /**
     * Get theme preview URL
     */
    public function getThemePreviewUrl(string $theme): string
    {
        $config = $this->getThemeConfig($theme);
        $preview = $config['preview'] ?? 'default-preview.png';
        
        return asset("superauth/themes/{$preview}");
    }

    /**
     * Get theme CSS file path
     */
    public function getThemeCssPath(string $theme = null): string
    {
        $theme = $theme ?: $this->getCurrentTheme();
        return "superauth/themes/{$theme}.css";
    }

    /**
     * Get theme JavaScript file path
     */
    public function getThemeJsPath(string $theme = null): string
    {
        $theme = $theme ?: $this->getCurrentTheme();
        return "superauth/themes/{$theme}.js";
    }

    /**
     * Clear theme cache
     */
    public function clearThemeCache(): void
    {
        $themes = array_keys($this->getAvailableThemes());
        
        foreach ($themes as $theme) {
            $cacheKey = $this->cachePrefix . "config:{$theme}";
            Cache::forget($cacheKey);
        }
    }

    /**
     * Get theme for specific feature
     */
    public function getFeatureTheme(string $feature): string
    {
        $featureThemes = [
            'authentication' => 'glass-morphism',
            'authorization' => 'corporate',
            'security' => 'dark',
            'notifications' => 'minimal',
            'ai' => 'colorful',
        ];
        
        return $featureThemes[$feature] ?? $this->getCurrentTheme();
    }

    /**
     * Get theme compatibility
     */
    public function getThemeCompatibility(string $theme): array
    {
        $config = $this->getThemeConfig($theme);
        $features = $config['features'] ?? [];
        
        return [
            'mobile' => true,
            'tablet' => true,
            'desktop' => true,
            'accessibility' => $features['dark_mode'] ?? false,
            'performance' => !($features['blur_effects'] ?? false),
        ];
    }
}
