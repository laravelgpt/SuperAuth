<?php

namespace SuperAuth\Tests\Feature;

use SuperAuth\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BasicTest extends TestCase
{
    use RefreshDatabase;

    public function test_package_structure_exists()
    {
        // Test that the package structure is correct
        $this->assertTrue(file_exists(__DIR__ . '/../../src/SuperAuthServiceProvider.php'));
        $this->assertTrue(file_exists(__DIR__ . '/../../config/superauth.php'));
        $this->assertTrue(file_exists(__DIR__ . '/../../database/migrations'));
    }

    public function test_service_provider_can_be_loaded()
    {
        $provider = new \SuperAuth\SuperAuthServiceProvider(app());
        $this->assertInstanceOf(\SuperAuth\SuperAuthServiceProvider::class, $provider);
    }

    public function test_config_file_is_valid()
    {
        $config = config('superauth');
        $this->assertIsArray($config);
        $this->assertArrayHasKey('route_prefix', $config);
        $this->assertArrayHasKey('social_providers', $config);
        $this->assertArrayHasKey('otp', $config);
        $this->assertArrayHasKey('breach_check', $config);
        $this->assertArrayHasKey('password_strength', $config);
    }

    public function test_migrations_exist()
    {
        $migrationPath = __DIR__ . '/../../database/migrations';
        $this->assertTrue(is_dir($migrationPath));
        
        $migrations = glob($migrationPath . '/*.php');
        $this->assertGreaterThan(0, count($migrations));
    }

    public function test_views_exist()
    {
        $viewsPath = __DIR__ . '/../../resources/views';
        $this->assertTrue(is_dir($viewsPath));
        
        $this->assertTrue(file_exists($viewsPath . '/layouts/auth.blade.php'));
        $this->assertTrue(file_exists($viewsPath . '/layouts/app.blade.php'));
        $this->assertTrue(file_exists($viewsPath . '/layouts/admin.blade.php'));
    }

    public function test_models_exist()
    {
        $this->assertTrue(class_exists(\SuperAuth\Models\User::class));
        $this->assertTrue(class_exists(\SuperAuth\Models\SocialAccount::class));
        $this->assertTrue(class_exists(\SuperAuth\Models\OtpVerification::class));
        $this->assertTrue(class_exists(\SuperAuth\Models\PasswordBreach::class));
        $this->assertTrue(class_exists(\SuperAuth\Models\Role::class));
        $this->assertTrue(class_exists(\SuperAuth\Models\Permission::class));
        $this->assertTrue(class_exists(\SuperAuth\Models\LoginHistory::class));
    }

    public function test_services_exist()
    {
        $this->assertTrue(class_exists(\SuperAuth\Services\BreachCheckService::class));
        $this->assertTrue(class_exists(\SuperAuth\Services\OtpService::class));
        $this->assertTrue(class_exists(\SuperAuth\Services\EnhancedBreachCheckService::class));
        $this->assertTrue(class_exists(\SuperAuth\Services\EnhancedPasswordStrengthService::class));
        $this->assertTrue(class_exists(\SuperAuth\Services\AiAgentService::class));
        $this->assertTrue(class_exists(\SuperAuth\Services\MultiChannelNotificationService::class));
    }

    public function test_livewire_components_exist()
    {
        $this->assertTrue(class_exists(\SuperAuth\Livewire\Auth\Login::class));
        $this->assertTrue(class_exists(\SuperAuth\Livewire\Auth\Register::class));
        $this->assertTrue(class_exists(\SuperAuth\Livewire\Admin\Dashboard::class));
        $this->assertTrue(class_exists(\SuperAuth\Livewire\Profile\Profile::class));
        $this->assertTrue(class_exists(\SuperAuth\Livewire\Admin\AiDashboard::class));
    }

    public function test_controllers_exist()
    {
        $this->assertTrue(class_exists(\SuperAuth\Http\Controllers\AuthController::class));
        $this->assertTrue(class_exists(\SuperAuth\Http\Controllers\SocialAuthController::class));
        $this->assertTrue(class_exists(\SuperAuth\Http\Controllers\AdminController::class));
    }

    public function test_middleware_exist()
    {
        $this->assertTrue(class_exists(\SuperAuth\Middleware\SecurityHeadersMiddleware::class));
        $this->assertTrue(class_exists(\SuperAuth\Middleware\RateLimitMiddleware::class));
        $this->assertTrue(class_exists(\SuperAuth\Middleware\RoleBasedAccessMiddleware::class));
        $this->assertTrue(class_exists(\SuperAuth\Middleware\PermissionBasedAccessMiddleware::class));
    }

    public function test_mail_classes_exist()
    {
        $this->assertTrue(class_exists(\SuperAuth\Mail\OtpMail::class));
    }

    public function test_console_commands_exist()
    {
        $this->assertTrue(class_exists(\SuperAuth\Console\Commands\InstallCommand::class));
        $this->assertTrue(class_exists(\SuperAuth\Console\Commands\CreateDefaultRolesCommand::class));
        $this->assertTrue(class_exists(\SuperAuth\Console\Commands\CleanupExpiredRolesCommand::class));
        $this->assertTrue(class_exists(\SuperAuth\Console\Commands\RoleStatsCommand::class));
    }
}