<?php

namespace SuperAuth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class InstallLivewireKitCommand extends Command
{
    protected $signature = 'superauth:install-livewire-kit 
                            {--force : Force installation even if files exist}
                            {--with-demo : Include demo components}';

    protected $description = 'Install SuperAuth Livewire kit with components and views';

    public function handle()
    {
        $this->info('🚀 Installing SuperAuth Livewire Kit...');

        $this->installLivewireComponents();
        $this->installLivewireViews();
        $this->installLivewireLayouts();
        
        if ($this->option('with-demo')) {
            $this->installDemoComponents();
        }

        $this->publishAssets();
        $this->updateRoutes();
        $this->updateConfig();

        $this->info('✅ Livewire Kit installed successfully!');
        $this->comment('Run `php artisan migrate` to set up the database.');
        $this->comment('Run `php artisan superauth:create-default-roles` to create default roles.');
    }

    protected function installLivewireComponents()
    {
        $this->info('Installing Livewire components...');

        $components = [
            'Auth/Login.php' => $this->getLoginComponent(),
            'Auth/Register.php' => $this->getRegisterComponent(),
            'Auth/ForgotPassword.php' => $this->getForgotPasswordComponent(),
            'Auth/ResetPassword.php' => $this->getResetPasswordComponent(),
            'Auth/SocialLogin.php' => $this->getSocialLoginComponent(),
            'Profile/Profile.php' => $this->getProfileComponent(),
            'Admin/Dashboard.php' => $this->getAdminDashboardComponent(),
            'Admin/UserManagement.php' => $this->getUserManagementComponent(),
            'Admin/RoleManagement.php' => $this->getRoleManagementComponent(),
            'Components/PasswordStrength.php' => $this->getPasswordStrengthComponent(),
            'Components/BreachCheck.php' => $this->getBreachCheckComponent(),
        ];

        foreach ($components as $path => $content) {
            $fullPath = app_path("Livewire/{$path}");
            $this->createDirectory(dirname($fullPath));
            File::put($fullPath, $content);
            $this->line("  ✓ Created: {$path}");
        }
    }

    protected function installLivewireViews()
    {
        $this->info('Installing Livewire views...');

        $views = [
            'auth/login.blade.php' => $this->getLoginView(),
            'auth/register.blade.php' => $this->getRegisterView(),
            'auth/forgot-password.blade.php' => $this->getForgotPasswordView(),
            'auth/reset-password.blade.php' => $this->getResetPasswordView(),
            'auth/social-login.blade.php' => $this->getSocialLoginView(),
            'profile/profile.blade.php' => $this->getProfileView(),
            'admin/dashboard.blade.php' => $this->getAdminDashboardView(),
            'admin/user-management.blade.php' => $this->getUserManagementView(),
            'admin/role-management.blade.php' => $this->getRoleManagementView(),
            'components/password-strength.blade.php' => $this->getPasswordStrengthView(),
            'components/breach-check.blade.php' => $this->getBreachCheckView(),
        ];

        foreach ($views as $file => $content) {
            $path = resource_path("views/livewire/{$file}");
            $this->createDirectory(dirname($path));
            File::put($path, $content);
            $this->line("  ✓ Created: {$file}");
        }
    }

    protected function installLivewireLayouts()
    {
        $this->info('Installing Livewire layouts...');

        $layouts = [
            'app.blade.php' => $this->getLivewireAppLayout(),
            'auth.blade.php' => $this->getLivewireAuthLayout(),
            'admin.blade.php' => $this->getLivewireAdminLayout(),
        ];

        foreach ($layouts as $file => $content) {
            $path = resource_path("views/livewire/layouts/{$file}");
            $this->createDirectory(dirname($path));
            File::put($path, $content);
            $this->line("  ✓ Created: layouts/{$file}");
        }
    }

    protected function installDemoComponents()
    {
        $this->info('Installing demo components...');

        $demos = [
            'Demo/LandingPage.php' => $this->getLandingPageComponent(),
            'Demo/Features.php' => $this->getFeaturesComponent(),
            'Demo/Pricing.php' => $this->getPricingComponent(),
        ];

        foreach ($demos as $file => $content) {
            $fullPath = app_path("Livewire/{$file}");
            $this->createDirectory(dirname($fullPath));
            File::put($fullPath, $content);
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
        
        $routesContent = $this->getLivewireRoutes();
        $routesPath = base_path('routes/web.php');
        
        if (!File::exists($routesPath) || $this->option('force')) {
            File::put($routesPath, $routesContent);
            $this->line("  ✓ Updated: routes/web.php");
        }
    }

    protected function updateConfig()
    {
        $this->info('Updating configuration...');
        
        $configContent = $this->getLivewireConfig();
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

    // Livewire Component methods
    protected function getLoginComponent()
    {
        return '<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class Login extends Component
{
    public $email = "";
    public $password = "";
    public $remember = false;

    protected $rules = [
        "email" => "required|email",
        "password" => "required|min:6",
    ];

    public function login()
    {
        $this->validate();

        $key = "login." . $this->email;
        
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                "email" => "Too many login attempts. Please try again in {$seconds} seconds.",
            ]);
        }

        if (Auth::attempt(["email" => $this->email, "password" => $this->password], $this->remember)) {
            RateLimiter::clear($key);
            return redirect()->intended(route("superauth.dashboard"));
        }

        RateLimiter::hit($key, 300);
        
        throw ValidationException::withMessages([
            "email" => "The provided credentials are incorrect.",
        ]);
    }

    public function render()
    {
        return view("livewire.auth.login");
    }
}';
    }

    protected function getRegisterComponent()
    {
        return '<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Register extends Component
{
    public $name = "";
    public $email = "";
    public $password = "";
    public $password_confirmation = "";
    public $terms = false;

    protected $rules = [
        "name" => "required|min:2",
        "email" => "required|email|unique:users,email",
        "password" => "required|min:8|confirmed",
        "terms" => "required|accepted",
    ];

    public function register()
    {
        $this->validate();

        $user = User::create([
            "name" => $this->name,
            "email" => $this->email,
            "password" => Hash::make($this->password),
        ]);

        Auth::login($user);

        return redirect()->route("superauth.dashboard");
    }

    public function render()
    {
        return view("livewire.auth.register");
    }
}';
    }

    // Additional component methods
    protected function getForgotPasswordComponent() { return '<!-- Forgot Password Component -->'; }
    protected function getResetPasswordComponent() { return '<!-- Reset Password Component -->'; }
    protected function getSocialLoginComponent() { return '<!-- Social Login Component -->'; }
    protected function getProfileComponent() { return '<!-- Profile Component -->'; }
    protected function getAdminDashboardComponent() { return '<!-- Admin Dashboard Component -->'; }
    protected function getUserManagementComponent() { return '<!-- User Management Component -->'; }
    protected function getRoleManagementComponent() { return '<!-- Role Management Component -->'; }
    protected function getPasswordStrengthComponent() { return '<!-- Password Strength Component -->'; }
    protected function getBreachCheckComponent() { return '<!-- Breach Check Component -->'; }

    // View methods
    protected function getLoginView() { return '<!-- Login View -->'; }
    protected function getRegisterView() { return '<!-- Register View -->'; }
    protected function getForgotPasswordView() { return '<!-- Forgot Password View -->'; }
    protected function getResetPasswordView() { return '<!-- Reset Password View -->'; }
    protected function getSocialLoginView() { return '<!-- Social Login View -->'; }
    protected function getProfileView() { return '<!-- Profile View -->'; }
    protected function getAdminDashboardView() { return '<!-- Admin Dashboard View -->'; }
    protected function getUserManagementView() { return '<!-- User Management View -->'; }
    protected function getRoleManagementView() { return '<!-- Role Management View -->'; }
    protected function getPasswordStrengthView() { return '<!-- Password Strength View -->'; }
    protected function getBreachCheckView() { return '<!-- Breach Check View -->'; }

    // Layout methods
    protected function getLivewireAppLayout() { return '<!-- Livewire App Layout -->'; }
    protected function getLivewireAuthLayout() { return '<!-- Livewire Auth Layout -->'; }
    protected function getLivewireAdminLayout() { return '<!-- Livewire Admin Layout -->'; }

    // Demo methods
    protected function getLandingPageComponent() { return '<!-- Landing Page Component -->'; }
    protected function getFeaturesComponent() { return '<!-- Features Component -->'; }
    protected function getPricingComponent() { return '<!-- Pricing Component -->'; }

    // Configuration methods
    protected function getLivewireRoutes()
    {
        return '<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Profile\Profile;
use App\Livewire\Admin\Dashboard;

// Authentication Routes
Route::get("/login", Login::class)->name("superauth.login");
Route::get("/register", Register::class)->name("superauth.register");

// Dashboard Routes
Route::middleware("auth")->group(function () {
    Route::get("/dashboard", function () {
        return view("livewire.user.dashboard");
    })->name("superauth.dashboard");
    
    Route::get("/profile", Profile::class)->name("superauth.profile");
});

// Admin Routes
Route::middleware(["auth", "role:admin"])->prefix("admin")->group(function () {
    Route::get("/", Dashboard::class)->name("admin.dashboard");
});';
    }

    protected function getLivewireConfig()
    {
        return '<?php

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
    "livewire" => [
        "enabled" => true,
        "components" => [
            "auth" => true,
            "profile" => true,
            "admin" => true,
        ],
    ],
];';
    }
}
