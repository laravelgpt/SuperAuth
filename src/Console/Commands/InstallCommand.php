<?php

namespace SuperAuth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'superauth:install {--force : Force installation even if already installed}';
    protected $description = 'Install SuperAuth package';

    public function handle()
    {
        $this->info('🚀 Installing SuperAuth Package...');

        // Publish configuration
        $this->publishConfiguration();

        // Publish migrations
        $this->publishMigrations();

        // Publish views
        $this->publishViews();

        // Publish assets
        $this->publishAssets();

        // Run migrations
        $this->runMigrations();

        // Create default roles and permissions
        $this->createDefaultRoles();

        // Create default admin user
        $this->createDefaultAdmin();

        $this->info('✅ SuperAuth package installed successfully!');
        $this->info('📚 Documentation: https://github.com/laravelgpt/SuperAuth');
    }

    protected function publishConfiguration()
    {
        $this->info('📝 Publishing configuration...');
        Artisan::call('vendor:publish', [
            '--tag' => 'superauth-config',
            '--force' => $this->option('force'),
        ]);
    }

    protected function publishMigrations()
    {
        $this->info('🗄️ Publishing migrations...');
        Artisan::call('vendor:publish', [
            '--tag' => 'superauth-migrations',
            '--force' => $this->option('force'),
        ]);
    }

    protected function publishViews()
    {
        $this->info('🎨 Publishing views...');
        Artisan::call('vendor:publish', [
            '--tag' => 'superauth-views',
            '--force' => $this->option('force'),
        ]);
    }

    protected function publishAssets()
    {
        $this->info('📦 Publishing assets...');
        Artisan::call('vendor:publish', [
            '--tag' => 'superauth-assets',
            '--force' => $this->option('force'),
        ]);
    }

    protected function runMigrations()
    {
        $this->info('🔄 Running migrations...');
        Artisan::call('migrate', ['--force' => true]);
    }

    protected function createDefaultRoles()
    {
        $this->info('👥 Creating default roles and permissions...');
        Artisan::call('superauth:create-default-roles');
    }

    protected function createDefaultAdmin()
    {
        if ($this->confirm('Do you want to create a default admin user?')) {
            $name = $this->ask('Admin name', 'Super Admin');
            $email = $this->ask('Admin email', 'admin@example.com');
            $password = $this->secret('Admin password');

            if ($name && $email && $password) {
                $user = \SuperAuth\Models\User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => bcrypt($password),
                    'email_verified_at' => now(),
                ]);

                $user->assignRole('super_admin');

                $this->info('✅ Default admin user created successfully!');
            }
        }
    }
}
