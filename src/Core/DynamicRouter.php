<?php

namespace SuperAuth\Core;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use SuperAuth\Core\FeatureManager;

class DynamicRouter
{
    protected FeatureManager $featureManager;
    protected array $routes = [];
    protected array $middleware = [];

    public function __construct(FeatureManager $featureManager)
    {
        $this->featureManager = $featureManager;
        $this->loadRouteConfigurations();
    }

    /**
     * Load route configurations from config files
     */
    protected function loadRouteConfigurations(): void
    {
        $this->routes = Config::get('superauth.routes', []);
        $this->middleware = Config::get('superauth.middleware', []);
    }

    /**
     * Register all dynamic routes
     */
    public function registerRoutes(): void
    {
        $this->registerWebRoutes();
        $this->registerApiRoutes();
        $this->registerAuthRoutes();
        $this->registerAdminRoutes();
        $this->registerFeatureRoutes();
    }

    /**
     * Register web routes
     */
    protected function registerWebRoutes(): void
    {
        Route::middleware(['web'])->group(function () {
            // Dashboard routes
            if ($this->featureManager->isEnabled('dashboard')) {
                Route::get('/', [\SuperAuth\Http\Controllers\DashboardController::class, 'index'])->name('superauth.dashboard');
                Route::get('/dashboard', [\SuperAuth\Http\Controllers\DashboardController::class, 'index'])->name('superauth.dashboard');
            }

            // Profile routes
            if ($this->featureManager->isEnabled('profile')) {
                Route::get('/profile', [\SuperAuth\Http\Controllers\ProfileController::class, 'show'])->name('superauth.profile');
                Route::put('/profile', [\SuperAuth\Http\Controllers\ProfileController::class, 'update'])->name('superauth.profile.update');
            }

            // Theme routes
            Route::post('/theme', [\SuperAuth\Http\Controllers\ThemeController::class, 'toggle'])->name('superauth.theme');
        });
    }

    /**
     * Register API routes
     */
    protected function registerApiRoutes(): void
    {
        Route::prefix('api')->middleware(['api'])->group(function () {
            // Authentication API
            if ($this->featureManager->isEnabled('authentication')) {
                Route::post('/auth/login', [\SuperAuth\Http\Controllers\Api\AuthController::class, 'login']);
                Route::post('/auth/register', [\SuperAuth\Http\Controllers\Api\AuthController::class, 'register']);
                Route::post('/auth/logout', [\SuperAuth\Http\Controllers\Api\AuthController::class, 'logout']);
                Route::post('/auth/refresh', [\SuperAuth\Http\Controllers\Api\AuthController::class, 'refresh']);
            }

            // User API
            if ($this->featureManager->isEnabled('users')) {
                Route::middleware(['auth:sanctum'])->group(function () {
                    Route::get('/users', [\SuperAuth\Http\Controllers\Api\UserController::class, 'index']);
                    Route::get('/users/{user}', [\SuperAuth\Http\Controllers\Api\UserController::class, 'show']);
                    Route::put('/users/{user}', [\SuperAuth\Http\Controllers\Api\UserController::class, 'update']);
                    Route::delete('/users/{user}', [\SuperAuth\Http\Controllers\Api\UserController::class, 'destroy']);
                });
            }

            // Security API
            if ($this->featureManager->isEnabled('security')) {
                Route::middleware(['auth:sanctum'])->group(function () {
                    Route::post('/security/breach-check', [\SuperAuth\Http\Controllers\Api\SecurityController::class, 'breachCheck']);
                    Route::post('/security/password-strength', [\SuperAuth\Http\Controllers\Api\SecurityController::class, 'passwordStrength']);
                    Route::get('/security/login-history', [\SuperAuth\Http\Controllers\Api\SecurityController::class, 'loginHistory']);
                });
            }
        });
    }

    /**
     * Register authentication routes
     */
    protected function registerAuthRoutes(): void
    {
        Route::middleware(['web'])->group(function () {
            // Login routes
            Route::get('/login', [\SuperAuth\Http\Controllers\AuthController::class, 'showLoginForm'])->name('superauth.login');
            Route::post('/login', [\SuperAuth\Http\Controllers\AuthController::class, 'login'])->name('superauth.login');

            // Registration routes
            Route::get('/register', [\SuperAuth\Http\Controllers\AuthController::class, 'showRegisterForm'])->name('superauth.register');
            Route::post('/register', [\SuperAuth\Http\Controllers\AuthController::class, 'register'])->name('superauth.register');

            // Password reset routes
            Route::get('/forgot-password', [\SuperAuth\Http\Controllers\AuthController::class, 'showForgotPasswordForm'])->name('superauth.forgot-password');
            Route::post('/forgot-password', [\SuperAuth\Http\Controllers\AuthController::class, 'forgotPassword'])->name('superauth.forgot-password');
            Route::get('/reset-password/{token}', [\SuperAuth\Http\Controllers\AuthController::class, 'showResetPasswordForm'])->name('superauth.reset-password');
            Route::post('/reset-password', [\SuperAuth\Http\Controllers\AuthController::class, 'resetPassword'])->name('superauth.reset-password');

            // Email verification routes
            Route::get('/verify-email', [\SuperAuth\Http\Controllers\AuthController::class, 'showVerifyEmailForm'])->name('superauth.verify-email');
            Route::post('/verify-email/resend', [\SuperAuth\Http\Controllers\AuthController::class, 'resendVerification'])->name('superauth.verify-email.resend');

            // Two-factor authentication routes
            Route::get('/two-factor', [\SuperAuth\Http\Controllers\AuthController::class, 'showTwoFactorForm'])->name('superauth.two-factor');
            Route::post('/two-factor/verify', [\SuperAuth\Http\Controllers\AuthController::class, 'verifyTwoFactor'])->name('superauth.two-factor.verify');
            Route::post('/two-factor/recovery', [\SuperAuth\Http\Controllers\AuthController::class, 'verifyRecoveryCode'])->name('superauth.two-factor.recovery');

            // Social authentication routes
            if ($this->featureManager->isEnabled('social_auth')) {
                Route::get('/auth/{provider}', [\SuperAuth\Http\Controllers\SocialAuthController::class, 'redirect'])->name('superauth.social.redirect');
                Route::get('/auth/{provider}/callback', [\SuperAuth\Http\Controllers\SocialAuthController::class, 'callback'])->name('superauth.social.callback');
            }

            // Logout route
            Route::post('/logout', [\SuperAuth\Http\Controllers\AuthController::class, 'logout'])->name('superauth.logout');
        });
    }

    /**
     * Register admin routes
     */
    protected function registerAdminRoutes(): void
    {
        Route::prefix('admin')->middleware(['web', 'auth', 'role:admin'])->group(function () {
            // Admin dashboard
            Route::get('/', [\SuperAuth\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
            
            // User management
            Route::get('/users', [\SuperAuth\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
            Route::get('/users/{user}', [\SuperAuth\Http\Controllers\AdminController::class, 'showUser'])->name('admin.users.show');
            Route::put('/users/{user}', [\SuperAuth\Http\Controllers\AdminController::class, 'updateUser'])->name('admin.users.update');
            Route::delete('/users/{user}', [\SuperAuth\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.users.delete');

            // Role management
            Route::get('/roles', [\SuperAuth\Http\Controllers\AdminController::class, 'roles'])->name('admin.roles');
            Route::post('/roles', [\SuperAuth\Http\Controllers\AdminController::class, 'createRole'])->name('admin.roles.create');
            Route::put('/roles/{role}', [\SuperAuth\Http\Controllers\AdminController::class, 'updateRole'])->name('admin.roles.update');
            Route::delete('/roles/{role}', [\SuperAuth\Http\Controllers\AdminController::class, 'deleteRole'])->name('admin.roles.delete');

            // Security management
            Route::get('/security', [\SuperAuth\Http\Controllers\AdminController::class, 'security'])->name('admin.security');
            Route::get('/security/breaches', [\SuperAuth\Http\Controllers\AdminController::class, 'breaches'])->name('admin.security.breaches');
            Route::get('/security/logs', [\SuperAuth\Http\Controllers\AdminController::class, 'securityLogs'])->name('admin.security.logs');

            // AI Dashboard
            if ($this->featureManager->isEnabled('ai')) {
                Route::get('/ai', [\SuperAuth\Http\Controllers\AdminController::class, 'aiDashboard'])->name('admin.ai');
                Route::get('/ai/insights', [\SuperAuth\Http\Controllers\AdminController::class, 'aiInsights'])->name('admin.ai.insights');
            }
        });
    }

    /**
     * Register feature-specific routes
     */
    protected function registerFeatureRoutes(): void
    {
        foreach ($this->featureManager->getFeatures() as $feature => $config) {
            if ($this->featureManager->isEnabled($feature)) {
                $this->registerFeatureRoute($feature, $config);
            }
        }
    }

    /**
     * Register routes for a specific feature
     */
    protected function registerFeatureRoute(string $feature, array $config): void
    {
        $routes = $config['routes'] ?? [];
        
        foreach ($routes as $route) {
            $method = $route['method'] ?? 'get';
            $path = $route['path'] ?? '/';
            $action = $route['action'] ?? null;
            $name = $route['name'] ?? null;
            $middleware = $route['middleware'] ?? [];
            
            if ($action && $name) {
                Route::middleware($middleware)->{$method}($path, $action)->name($name);
            }
        }
    }

    /**
     * Generate routes dynamically based on configuration
     */
    public function generateRoutes(array $config): array
    {
        $generatedRoutes = [];
        
        foreach ($config as $group => $routes) {
            $generatedRoutes[$group] = [];
            
            foreach ($routes as $route) {
                $generatedRoutes[$group][] = $this->generateRoute($route);
            }
        }
        
        return $generatedRoutes;
    }

    /**
     * Generate a single route
     */
    protected function generateRoute(array $route): array
    {
        return [
            'method' => $route['method'] ?? 'get',
            'path' => $route['path'] ?? '/',
            'action' => $route['action'] ?? null,
            'name' => $route['name'] ?? null,
            'middleware' => $route['middleware'] ?? [],
            'parameters' => $route['parameters'] ?? [],
            'constraints' => $route['constraints'] ?? []
        ];
    }

    /**
     * Get all registered routes
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * Get routes for a specific feature
     */
    public function getFeatureRoutes(string $feature): array
    {
        return $this->routes[$feature] ?? [];
    }

    /**
     * Add a new route dynamically
     */
    public function addRoute(string $feature, array $route): void
    {
        if (!isset($this->routes[$feature])) {
            $this->routes[$feature] = [];
        }
        
        $this->routes[$feature][] = $route;
    }

    /**
     * Remove a route
     */
    public function removeRoute(string $feature, string $routeName): void
    {
        if (isset($this->routes[$feature])) {
            $this->routes[$feature] = array_filter(
                $this->routes[$feature],
                fn($route) => ($route['name'] ?? '') !== $routeName
            );
        }
    }
}
