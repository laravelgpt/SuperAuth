<?php

namespace SuperAuth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class SetupCommand extends Command
{
    protected $signature = 'superauth:setup {--force : Force setup even if already configured}';
    protected $description = 'Setup SuperAuth database and create default data';

    public function handle()
    {
        $this->info('🚀 Setting up SuperAuth...');

        // Check if already set up
        if (!$this->option('force') && $this->isAlreadySetup()) {
            $this->warn('SuperAuth is already set up. Use --force to reinstall.');
            return;
        }

        // Publish migrations
        $this->publishMigrations();

        // Run migrations
        $this->runMigrations();

        // Create default roles and permissions
        $this->createDefaultRoles();

        $this->info('✅ SuperAuth setup completed successfully!');
        $this->info('📚 Documentation: https://github.com/laravelgpt/SuperAuth');
    }

    protected function isAlreadySetup(): bool
    {
        try {
            return Schema::hasTable('roles') && Schema::hasTable('permissions');
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function publishMigrations()
    {
        $this->info('📝 Publishing migrations...');
        Artisan::call('vendor:publish', [
            '--tag' => 'superauth-migrations',
            '--force' => $this->option('force'),
        ]);
    }

    protected function runMigrations()
    {
        $this->info('🔄 Running migrations...');
        try {
            Artisan::call('migrate', ['--force' => true]);
            $this->info('✅ Migrations completed successfully!');
        } catch (\Exception $e) {
            $this->error('❌ Migration failed: ' . $e->getMessage());
            $this->error('Please run: php artisan migrate');
            throw $e;
        }
    }

    protected function createDefaultRoles()
    {
        $this->info('👥 Creating default roles and permissions...');
        try {
            Artisan::call('superauth:create-default-roles');
            $this->info('✅ Default roles and permissions created!');
        } catch (\Exception $e) {
            $this->error('❌ Failed to create default roles: ' . $e->getMessage());
            $this->error('Please run: php artisan superauth:create-default-roles');
        }
    }
}
