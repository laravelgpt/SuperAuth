<?php

namespace SuperAuth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class InstallLaravelKitCommand extends Command
{
    protected $signature = 'superauth:install-laravel-kit 
                            {--force : Force installation even if files exist}
                            {--with-demo : Include demo components}';

    protected $description = 'Install SuperAuth Laravel Blade kit with components and layouts';

    public function handle()
    {
        $this->info('🚀 Installing SuperAuth Laravel Blade Kit...');

        $this->installLaravelComponents();
        $this->installLaravelLayouts();
        $this->installLaravelViews();
        
        if ($this->option('with-demo')) {
            $this->installDemoComponents();
        }

        $this->publishAssets();
        $this->updateRoutes();
        $this->updateConfig();

        $this->info('✅ Laravel Blade Kit installed successfully!');
        $this->comment('Run `php artisan migrate` to set up the database.');
        $this->comment('Run `php artisan superauth:create-default-roles` to create default roles.');
    }

    protected function installLaravelComponents()
    {
        $this->info('Installing Laravel Blade components...');

        $components = [
            'auth/login-form.blade.php' => $this->getLoginFormComponent(),
            'auth/register-form.blade.php' => $this->getRegisterFormComponent(),
            'auth/forgot-password-form.blade.php' => $this->getForgotPasswordFormComponent(),
            'auth/reset-password-form.blade.php' => $this->getResetPasswordFormComponent(),
            'auth/social-login.blade.php' => $this->getSocialLoginComponent(),
            'profile/user-profile.blade.php' => $this->getUserProfileComponent(),
            'profile/edit-profile.blade.php' => $this->getEditProfileComponent(),
            'admin/user-management.blade.php' => $this->getUserManagementComponent(),
            'admin/role-management.blade.php' => $this->getRoleManagementComponent(),
            'security/password-strength.blade.php' => $this->getPasswordStrengthComponent(),
            'security/breach-check.blade.php' => $this->getBreachCheckComponent(),
        ];

        foreach ($components as $path => $content) {
            $fullPath = resource_path("views/laravel/components/{$path}");
            $this->createDirectory(dirname($fullPath));
            File::put($fullPath, $content);
            $this->line("  ✓ Created: {$path}");
        }
    }

    protected function installLaravelLayouts()
    {
        $this->info('Installing Laravel Blade layouts...');

        $layouts = [
            'app.blade.php' => $this->getLaravelAppLayout(),
            'auth.blade.php' => $this->getLaravelAuthLayout(),
            'admin.blade.php' => $this->getLaravelAdminLayout(),
        ];

        foreach ($layouts as $file => $content) {
            $path = resource_path("views/laravel/layouts/{$file}");
            $this->createDirectory(dirname($path));
            File::put($path, $content);
            $this->line("  ✓ Created: layouts/{$file}");
        }
    }

    protected function installLaravelViews()
    {
        $this->info('Installing Laravel Blade views...');

        $views = [
            'auth/login.blade.php' => $this->getLaravelLoginView(),
            'auth/register.blade.php' => $this->getLaravelRegisterView(),
            'auth/forgot-password.blade.php' => $this->getLaravelForgotPasswordView(),
            'auth/reset-password.blade.php' => $this->getLaravelResetPasswordView(),
            'admin/dashboard.blade.php' => $this->getLaravelAdminDashboardView(),
            'admin/users.blade.php' => $this->getLaravelAdminUsersView(),
            'admin/roles.blade.php' => $this->getLaravelAdminRolesView(),
            'user/dashboard.blade.php' => $this->getLaravelUserDashboardView(),
            'user/profile.blade.php' => $this->getLaravelUserProfileView(),
        ];

        foreach ($views as $file => $content) {
            $path = resource_path("views/laravel/{$file}");
            $this->createDirectory(dirname($path));
            File::put($path, $content);
            $this->line("  ✓ Created: {$file}");
        }
    }

    protected function installDemoComponents()
    {
        $this->info('Installing demo components...');

        $demos = [
            'demo/landing-page.blade.php' => $this->getLandingPageDemo(),
            'demo/features.blade.php' => $this->getFeaturesDemo(),
            'demo/pricing.blade.php' => $this->getPricingDemo(),
        ];

        foreach ($demos as $file => $content) {
            $path = resource_path("views/laravel/{$file}");
            $this->createDirectory(dirname($path));
            File::put($path, $content);
            $this->line("  ✓ Created: {$file}");
        }
    }

    protected function publishAssets()
    {
        $this->info('Publishing assets...');
        
        Artisan::call('vendor:publish', [
            '--tag' => 'superauth-views',
            '--force' => $this->option('force')
        ]);
        
        Artisan::call('vendor:publish', [
            '--tag' => 'superauth-assets',
            '--force' => $this->option('force')
        ]);
    }

    protected function updateRoutes()
    {
        $this->info('Updating routes...');
        
        $routesContent = $this->getLaravelRoutes();
        $routesPath = base_path('routes/web.php');
        
        if (!File::exists($routesPath) || $this->option('force')) {
            File::put($routesPath, $routesContent);
            $this->line("  ✓ Updated: routes/web.php");
        }
    }

    protected function updateConfig()
    {
        $this->info('Updating configuration...');
        
        $configContent = $this->getLaravelConfig();
        $configPath = config_path('superauth.php');
        
        if (!File::exists($configPath) || $this->option('force')) {
            File::put($configPath, $configContent);
            $this->line("  ✓ Updated: config/superauth.php");
        }
    }

    protected function createDirectory($path)
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    // Component content methods
    protected function getLoginFormComponent()
    {
        return '@extends("superauth::shared.layouts.auth")

@section("content")
<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
                Sign in to your account
            </h2>
        </div>
        
        <form class="mt-8 space-y-6" action="{{ route("superauth.login") }}" method="POST">
            @csrf
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Email Address
                </label>
                <input id="email" name="email" type="email" required 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Password
                </label>
                <input id="password" name="password" type="password" required 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
            </div>
            
            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    Sign in
                </button>
            </div>
        </form>
    </div>
</div>
@endsection';
    }

    protected function getRegisterFormComponent()
    {
        return '@extends("superauth::shared.layouts.auth")

@section("content")
<div class="min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
                Create your account
            </h2>
        </div>
        
        <form class="mt-8 space-y-6" action="{{ route("superauth.register") }}" method="POST">
            @csrf
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Full Name
                </label>
                <input id="name" name="name" type="text" required 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
            </div>
            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Email Address
                </label>
                <input id="email" name="email" type="email" required 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
            </div>
            
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Password
                </label>
                <input id="password" name="password" type="password" required 
                       class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 dark:bg-gray-700 dark:text-white">
            </div>
            
            <div>
                <button type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    Create Account
                </button>
            </div>
        </form>
    </div>
</div>
@endsection';
    }

    // Additional component methods would go here...
    protected function getForgotPasswordFormComponent() { return '<!-- Forgot Password Form Component -->'; }
    protected function getResetPasswordFormComponent() { return '<!-- Reset Password Form Component -->'; }
    protected function getSocialLoginComponent() { return '<!-- Social Login Component -->'; }
    protected function getUserProfileComponent() { return '<!-- User Profile Component -->'; }
    protected function getEditProfileComponent() { return '<!-- Edit Profile Component -->'; }
    protected function getUserManagementComponent() { return '<!-- User Management Component -->'; }
    protected function getRoleManagementComponent() { return '<!-- Role Management Component -->'; }
    protected function getPasswordStrengthComponent() { return '<!-- Password Strength Component -->'; }
    protected function getBreachCheckComponent() { return '<!-- Breach Check Component -->'; }

    // Layout methods
    protected function getLaravelAppLayout() { return '<!-- Laravel App Layout -->'; }
    protected function getLaravelAuthLayout() { return '<!-- Laravel Auth Layout -->'; }
    protected function getLaravelAdminLayout() { return '<!-- Laravel Admin Layout -->'; }

    // View methods
    protected function getLaravelLoginView() { return '<!-- Laravel Login View -->'; }
    protected function getLaravelRegisterView() { return '<!-- Laravel Register View -->'; }
    protected function getLaravelForgotPasswordView() { return '<!-- Laravel Forgot Password View -->'; }
    protected function getLaravelResetPasswordView() { return '<!-- Laravel Reset Password View -->'; }
    protected function getLaravelAdminDashboardView() { return '<!-- Laravel Admin Dashboard View -->'; }
    protected function getLaravelAdminUsersView() { return '<!-- Laravel Admin Users View -->'; }
    protected function getLaravelAdminRolesView() { return '<!-- Laravel Admin Roles View -->'; }
    protected function getLaravelUserDashboardView() { return '<!-- Laravel User Dashboard View -->'; }
    protected function getLaravelUserProfileView() { return '<!-- Laravel User Profile View -->'; }

    // Demo methods
    protected function getLandingPageDemo() { return '<!-- Landing Page Demo -->'; }
    protected function getFeaturesDemo() { return '<!-- Features Demo -->'; }
    protected function getPricingDemo() { return '<!-- Pricing Demo -->'; }

    // Configuration methods
    protected function getLaravelRoutes() { return '<?php

use Illuminate\Support\Facades\Route;
use SuperAuth\Http\Controllers\AuthController;
use SuperAuth\Http\Controllers\AdminController;

// Authentication Routes
Route::get("/login", [AuthController::class, "showLoginForm"])->name("superauth.login");
Route::post("/login", [AuthController::class, "login"]);
Route::get("/register", [AuthController::class, "showRegisterForm"])->name("superauth.register");
Route::post("/register", [AuthController::class, "register"]);
Route::post("/logout", [AuthController::class, "logout"])->name("superauth.logout");

// Admin Routes
Route::middleware(["auth", "role:admin"])->prefix("admin")->group(function () {
    Route::get("/", [AdminController::class, "dashboard"])->name("admin.dashboard");
    Route::get("/users", [AdminController::class, "users"])->name("admin.users");
    Route::get("/roles", [AdminController::class, "roles"])->name("admin.roles");
});

// Dashboard Route
Route::get("/dashboard", function () {
    return view("laravel.user.dashboard");
})->middleware("auth")->name("superauth.dashboard");'; }

    protected function getLaravelConfig() { return '<?php

return [
    "features" => [
        "authentication" => [
            "enabled" => true,
            "methods" => [
                "traditional" => true,
                "social" => true,
                "otp" => false,
            ],
        ],
        "authorization" => [
            "enabled" => true,
            "roles" => [
                "default_user_role" => "user",
                "default_admin_role" => "admin",
            ],
        ],
        "security" => [
            "enabled" => true,
            "password_breach_checking" => true,
            "password_strength_analysis" => true,
        ],
    ],
];'; }
}
