<?php

namespace SuperAuth;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use SuperAuth\Livewire\Auth\Login;
use SuperAuth\Livewire\Auth\Register;
use SuperAuth\Livewire\Auth\OtpVerification;
use SuperAuth\Livewire\Auth\SocialLogin;
use SuperAuth\Livewire\Auth\PasswordReset;
use SuperAuth\Livewire\Profile\Profile;
use SuperAuth\Livewire\Admin\Dashboard;
use SuperAuth\Livewire\Admin\UserManagement;
use SuperAuth\Livewire\Admin\RoleManagement;
use SuperAuth\Livewire\Admin\UserRoleAssignment;
use SuperAuth\Livewire\Admin\AiDashboard;
use SuperAuth\Livewire\User\Dashboard as UserDashboard;
use SuperAuth\Livewire\Components\PasswordStrength;
use SuperAuth\Livewire\Components\BreachCheck;
use SuperAuth\Livewire\Components\EnhancedPasswordStrength;
use SuperAuth\Livewire\Components\EnhancedBreachCheck;
use SuperAuth\Core\DynamicRouter;
use SuperAuth\Core\FeatureManager;
use SuperAuth\Core\ThemeManager;

class SuperAuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/superauth.php', 'superauth');
        $this->mergeConfigFrom(__DIR__.'/../config/superauth-routes.php', 'superauth.routes');
        
        // Register core services
        $this->app->singleton(FeatureManager::class);
        $this->app->singleton(ThemeManager::class);
        $this->app->singleton(DynamicRouter::class, function ($app) {
            return new DynamicRouter($app->make(FeatureManager::class));
        });
        
        // Register services
        $this->app->singleton(\SuperAuth\Services\SecureLoggingService::class);
        $this->app->singleton(\SuperAuth\Services\AiAgentService::class);
        $this->app->singleton(\SuperAuth\Services\IntelligentNotificationService::class);
        $this->app->singleton(\SuperAuth\Services\MultiChannelNotificationService::class);
        $this->app->singleton(\SuperAuth\Services\NotificationTestingService::class);
        $this->app->singleton(\SuperAuth\Services\ErrorReportingService::class);
        $this->app->singleton(\SuperAuth\Services\ErrorRecoveryService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Publish configuration
        $this->publishes([
            __DIR__.'/../config/superauth.php' => config_path('superauth.php'),
        ], 'superauth-config');

        // Publish migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'superauth-migrations');

        // Publish views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/superauth'),
        ], 'superauth-views');

        // Publish assets
        $this->publishes([
            __DIR__.'/../resources/css' => public_path('vendor/superauth/css'),
            __DIR__.'/../resources/js' => public_path('vendor/superauth/js'),
        ], 'superauth-assets');

        // Register dynamic routes (only if not in console or if routes are available)
        try {
            if (!$this->app->runningInConsole() || $this->app->runningUnitTests()) {
                $this->app->make(DynamicRouter::class)->registerRoutes();
            }
        } catch (\Exception $e) {
            // Silently fail during package discovery to avoid bootstrap issues
            if (!$this->app->runningInConsole()) {
                throw $e;
            }
        }

        // Publish bootstrap configuration
        $this->publishes([
            __DIR__.'/../bootstrap/app.php' => base_path('bootstrap/app.php'),
        ], 'superauth-bootstrap');

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'superauth');

        // Load routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/console.php');

        // Register middleware
        $this->registerMiddleware();

        // Register Livewire components
        $this->registerLivewireComponents();

        // Register console commands
        $this->registerConsoleCommands();
    }

    /**
     * Register middleware.
     */
    protected function registerMiddleware(): void
    {
        try {
            $router = $this->app['router'];
            
            // Register security middleware
            $router->aliasMiddleware('security.headers', \SuperAuth\Middleware\SecurityHeadersMiddleware::class);
            $router->aliasMiddleware('rate.limit', \SuperAuth\Middleware\RateLimitMiddleware::class);
            $router->aliasMiddleware('role.access', \SuperAuth\Middleware\RoleBasedAccessMiddleware::class);
            $router->aliasMiddleware('permission.access', \SuperAuth\Middleware\PermissionBasedAccessMiddleware::class);
            $router->aliasMiddleware('feature.access', \SuperAuth\Middleware\FeatureAccessMiddleware::class);
            $router->aliasMiddleware('error.handling', \SuperAuth\Middleware\ErrorHandlingMiddleware::class);
        
            // Apply security headers to all routes
            $router->pushMiddlewareToGroup('web', \SuperAuth\Middleware\SecurityHeadersMiddleware::class);
            
            // Apply error handling to all routes
            $router->pushMiddlewareToGroup('web', \SuperAuth\Middleware\ErrorHandlingMiddleware::class);
        } catch (\Exception $e) {
            // Silently fail during package discovery to avoid bootstrap issues
            if (!$this->app->runningInConsole()) {
                throw $e;
            }
        }
    }

    /**
     * Register Livewire components.
     */
    protected function registerLivewireComponents(): void
    {
        try {
            if (class_exists(\Livewire\Livewire::class)) {
                Livewire::component('superauth.login', Login::class);
                Livewire::component('superauth.register', Register::class);
                Livewire::component('superauth.otp-verification', OtpVerification::class);
                Livewire::component('superauth.social-login', SocialLogin::class);
                Livewire::component('superauth.password-reset', PasswordReset::class);
                Livewire::component('superauth.profile', Profile::class);
                Livewire::component('superauth.admin-dashboard', Dashboard::class);
                Livewire::component('superauth.user-management', UserManagement::class);
                Livewire::component('superauth.password-strength', PasswordStrength::class);
                Livewire::component('superauth.breach-check', BreachCheck::class);
                Livewire::component('superauth.enhanced-password-strength', EnhancedPasswordStrength::class);
                Livewire::component('superauth.enhanced-breach-check', EnhancedBreachCheck::class);
                Livewire::component('superauth.role-management', RoleManagement::class);
                Livewire::component('superauth.user-role-assignment', UserRoleAssignment::class);
                Livewire::component('superauth.ai-dashboard', AiDashboard::class);
                Livewire::component('superauth.user-dashboard', UserDashboard::class);
            }
        } catch (\Exception $e) {
            // Silently fail during package discovery to avoid bootstrap issues
            if (!$this->app->runningInConsole()) {
                throw $e;
            }
        }
    }

    /**
     * Register console commands.
     */

    protected function registerConsoleCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \SuperAuth\Console\Commands\InstallCommand::class,
                \SuperAuth\Console\Commands\SetupCommand::class,
                \SuperAuth\Console\Commands\InstallWizardCommand::class,
                \SuperAuth\Console\Commands\InstallLaravelKitCommand::class,
                \SuperAuth\Console\Commands\InstallLivewireKitCommand::class,
                \SuperAuth\Console\Commands\InstallVueKitCommand::class,
                \SuperAuth\Console\Commands\InstallReactKitCommand::class,
                \SuperAuth\Console\Commands\CreateDefaultRolesCommand::class,
                \SuperAuth\Console\Commands\CleanupExpiredRolesCommand::class,
                \SuperAuth\Console\Commands\RoleStatsCommand::class,
                \SuperAuth\Console\Commands\RemoveCommand::class,
            ]);
        }
    }
}
