<?php

namespace SuperAuth\Tests\Feature;

use SuperAuth\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SimpleTest extends TestCase
{
    use RefreshDatabase;

    public function test_package_can_be_loaded()
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
}
