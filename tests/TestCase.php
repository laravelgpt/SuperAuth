<?php

namespace SuperAuth\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use SuperAuth\SuperAuthServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionServiceProvider;

class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Run migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        
        // Create roles only if database is ready
        try {
            $this->createRoles();
        } catch (\Exception $e) {
            // Skip role creation if database is not ready
        }
    }

    protected function getPackageProviders($app)
    {
        return [
            // Only load core providers for testing
            PermissionServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Setup superauth config
        $app['config']->set('superauth', [
            'route_prefix' => 'auth',
            'social_providers' => [
                'google' => ['enabled' => true],
                'facebook' => ['enabled' => true],
                'github' => ['enabled' => true],
                'apple' => ['enabled' => true],
            ],
            'otp' => [
                'expires_in' => 10,
                'length' => 6,
                'max_attempts' => 3,
            ],
            'breach_check' => [
                'enabled' => true,
                'api_url' => 'https://api.pwnedpasswords.com/range/',
                'timeout' => 5,
                'cache_ttl' => 3600,
            ],
            'password_strength' => [
                'min_length' => 8,
                'require_uppercase' => true,
                'require_lowercase' => true,
                'require_numbers' => true,
                'require_symbols' => true,
                'min_score' => 3,
            ],
            'ui' => [
                'theme' => ['default' => 'light', 'allow_switching' => true],
                'colors' => [
                    'primary' => '#8B5CF6',
                    'secondary' => '#1E40AF',
                    'accent' => '#3B82F6',
                ],
                'glass_morphism' => ['enabled' => true],
            ],
            'admin' => [
                'route_prefix' => 'admin',
                'middleware' => ['auth', 'role:admin'],
                'per_page' => 15,
            ],
            'notifications' => [
                'enabled' => true,
                'channels' => ['database', 'mail'],
                'real_time' => true,
            ],
        ]);

        // Setup mail config
        $app['config']->set('mail.default', 'array');
        $app['config']->set('mail.mailers.array', [
            'transport' => 'array',
        ]);

        // Setup logging
        $app['config']->set('logging.channels.auth', [
            'driver' => 'single',
            'path' => storage_path('logs/auth.log'),
            'level' => 'info',
        ]);

        $app['config']->set('logging.channels.security', [
            'driver' => 'single',
            'path' => storage_path('logs/security.log'),
            'level' => 'warning',
        ]);

        $app['config']->set('logging.channels.admin', [
            'driver' => 'single',
            'path' => storage_path('logs/admin.log'),
            'level' => 'info',
        ]);
    }

    protected function createRoles()
    {
        // This would typically be done in a seeder or migration
        // For testing, we'll create roles directly
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            \Spatie\Permission\Models\Role::create(['name' => 'admin']);
            \Spatie\Permission\Models\Role::create(['name' => 'user']);
        }
    }

    protected function createUser(array $attributes = [])
    {
        return \SuperAuth\Models\User::factory()->create($attributes);
    }

    protected function createAdmin(array $attributes = [])
    {
        $user = $this->createUser(array_merge($attributes, ['is_admin' => true]));
        $user->assignRole('admin');
        return $user;
    }
}