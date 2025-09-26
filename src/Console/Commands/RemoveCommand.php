<?php

namespace SuperAuth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class RemoveCommand extends Command
{
    protected $signature = 'superauth:remove 
                            {--force : Force removal without confirmation}
                            {--keep-data : Keep user data and roles}
                            {--keep-config : Keep configuration files}
                            {--keep-views : Keep view files}
                            {--keep-assets : Keep published assets}';

    protected $description = 'Remove SuperAuth package and clean up all components';

    public function handle()
    {
        $this->displayWelcome();

        if (!$this->option('force')) {
            if (!$this->confirm('Are you sure you want to remove SuperAuth? This action cannot be undone!', false)) {
                $this->info('❌ Removal cancelled.');
                return;
            }
        }

        $this->info('🗑️ Starting SuperAuth removal process...');
        $this->newLine();

        // Step 1: Remove database components
        $this->removeDatabaseComponents();

        // Step 2: Remove published files
        $this->removePublishedFiles();

        // Step 3: Remove configuration
        $this->removeConfiguration();

        // Step 4: Remove views
        $this->removeViews();

        // Step 5: Remove assets
        $this->removeAssets();

        // Step 6: Remove routes
        $this->removeRoutes();

        // Step 7: Clean up service provider
        $this->cleanupServiceProvider();

        $this->displaySuccess();
    }

    protected function displayWelcome()
    {
        $this->newLine();
        $this->line('╔══════════════════════════════════════════════════════════════╗');
        $this->line('║                    🗑️ SuperAuth Removal                      ║');
        $this->line('║                                                              ║');
        $this->line('║  This command will remove SuperAuth and clean up:           ║');
        $this->line('║  • Database tables and data (optional)                      ║');
        $this->line('║  • Published configuration files                           ║');
        $this->line('║  • Published view files                                     ║');
        $this->line('║  • Published assets (CSS, JS, images)                      ║');
        $this->line('║  • Custom routes and middleware                             ║');
        $this->line('║  • Service provider registrations                          ║');
        $this->line('║                                                              ║');
        $this->line('║  Use --keep-data to preserve user data and roles           ║');
        $this->line('║  Use --keep-config to preserve configuration files         ║');
        $this->line('║  Use --keep-views to preserve view files                    ║');
        $this->line('║  Use --keep-assets to preserve published assets             ║');
        $this->line('║                                                              ║');
        $this->line('╚══════════════════════════════════════════════════════════════╝');
        $this->newLine();
    }

    protected function removeDatabaseComponents()
    {
        $this->info('🗄️ Removing database components...');

        if (!$this->option('keep-data')) {
            if ($this->confirm('Remove SuperAuth database tables and data?', true)) {
                $this->dropSuperAuthTables();
            }
        } else {
            $this->info('📊 Keeping user data and roles (--keep-data)');
        }

        $this->info('✅ Database cleanup completed');
        $this->newLine();
    }

    protected function dropSuperAuthTables()
    {
        $tables = [
            'login_histories',
            'user_roles',
            'role_permissions',
            'permissions',
            'roles',
            'social_accounts',
            'otp_verifications'
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                try {
                    Schema::dropIfExists($table);
                    $this->line("  ✓ Dropped table: {$table}");
                } catch (\Exception $e) {
                    $this->warn("  ⚠️ Could not drop table {$table}: " . $e->getMessage());
                }
            }
        }
    }

    protected function removePublishedFiles()
    {
        $this->info('📁 Removing published files...');

        $publishedFiles = [
            base_path('config/superauth.php'),
            base_path('config/superauth-env.php'),
            base_path('config/superauth-routes.php'),
            base_path('bootstrap/app.php'),
        ];

        foreach ($publishedFiles as $file) {
            if (File::exists($file)) {
                if ($this->option('keep-config') && str_contains($file, 'config/')) {
                    $this->line("  📋 Keeping config file: " . basename($file));
                    continue;
                }

                try {
                    File::delete($file);
                    $this->line("  ✓ Removed: " . basename($file));
                } catch (\Exception $e) {
                    $this->warn("  ⚠️ Could not remove " . basename($file) . ": " . $e->getMessage());
                }
            }
        }

        $this->info('✅ Published files cleanup completed');
        $this->newLine();
    }

    protected function removeConfiguration()
    {
        if ($this->option('keep-config')) {
            $this->info('📋 Keeping configuration files (--keep-config)');
            return;
        }

        $this->info('⚙️ Removing configuration...');

        // Remove from .env file
        $this->removeFromEnvFile();

        $this->info('✅ Configuration cleanup completed');
        $this->newLine();
    }

    protected function removeFromEnvFile()
    {
        $envFile = base_path('.env');
        if (!File::exists($envFile)) {
            return;
        }

        $envContent = File::get($envFile);
        $superauthLines = [];

        $lines = explode("\n", $envContent);
        foreach ($lines as $line) {
            if (str_contains($line, 'SUPERAUTH_')) {
                $superauthLines[] = $line;
            }
        }

        if (!empty($superauthLines)) {
            $this->line('  📝 Found SuperAuth environment variables:');
            foreach ($superauthLines as $line) {
                $this->line("    • " . trim($line));
            }

            if ($this->confirm('Remove SuperAuth environment variables from .env?', true)) {
                $newContent = str_replace($superauthLines, '', $envContent);
                File::put($envFile, $newContent);
                $this->line('  ✓ Removed SuperAuth environment variables');
            }
        }
    }

    protected function removeViews()
    {
        if ($this->option('keep-views')) {
            $this->info('👁️ Keeping view files (--keep-views)');
            return;
        }

        $this->info('👁️ Removing published views...');

        $viewDirectories = [
            resource_path('views/superauth'),
            resource_path('views/features'),
            resource_path('views/shared'),
            resource_path('views/layouts'),
        ];

        foreach ($viewDirectories as $directory) {
            if (File::isDirectory($directory)) {
                try {
                    File::deleteDirectory($directory);
                    $this->line("  ✓ Removed directory: " . basename($directory));
                } catch (\Exception $e) {
                    $this->warn("  ⚠️ Could not remove " . basename($directory) . ": " . $e->getMessage());
                }
            }
        }

        $this->info('✅ Views cleanup completed');
        $this->newLine();
    }

    protected function removeAssets()
    {
        if ($this->option('keep-assets')) {
            $this->info('🎨 Keeping published assets (--keep-assets)');
            return;
        }

        $this->info('🎨 Removing published assets...');

        $assetDirectories = [
            public_path('vendor/superauth'),
            public_path('css/superauth'),
            public_path('js/superauth'),
        ];

        foreach ($assetDirectories as $directory) {
            if (File::isDirectory($directory)) {
                try {
                    File::deleteDirectory($directory);
                    $this->line("  ✓ Removed assets: " . basename($directory));
                } catch (\Exception $e) {
                    $this->warn("  ⚠️ Could not remove " . basename($directory) . ": " . $e->getMessage());
                }
            }
        }

        $this->info('✅ Assets cleanup completed');
        $this->newLine();
    }

    protected function removeRoutes()
    {
        $this->info('🛣️ Removing custom routes...');

        // Remove SuperAuth routes from web.php
        $this->removeFromRoutesFile('web.php');
        
        // Remove SuperAuth routes from api.php
        $this->removeFromRoutesFile('api.php');

        $this->info('✅ Routes cleanup completed');
        $this->newLine();
    }

    protected function removeFromRoutesFile($filename)
    {
        $routesFile = base_path("routes/{$filename}");
        if (!File::exists($routesFile)) {
            return;
        }

        $content = File::get($routesFile);
        
        // Look for SuperAuth route groups
        if (str_contains($content, 'SuperAuth') || str_contains($content, 'superauth')) {
            if ($this->confirm("Remove SuperAuth routes from {$filename}?", true)) {
                // Remove lines containing SuperAuth
                $lines = explode("\n", $content);
                $newLines = [];
                
                foreach ($lines as $line) {
                    if (!str_contains($line, 'SuperAuth') && !str_contains($line, 'superauth')) {
                        $newLines[] = $line;
                    }
                }
                
                File::put($routesFile, implode("\n", $newLines));
                $this->line("  ✓ Cleaned routes in {$filename}");
            }
        }
    }

    protected function cleanupServiceProvider()
    {
        $this->info('🔧 Cleaning up service provider...');

        // Remove from config/app.php
        $this->removeFromConfigApp();

        $this->info('✅ Service provider cleanup completed');
        $this->newLine();
    }

    protected function removeFromConfigApp()
    {
        $configFile = config_path('app.php');
        if (!File::exists($configFile)) {
            return;
        }

        $content = File::get($configFile);
        
        if (str_contains($content, 'SuperAuth\\SuperAuthServiceProvider')) {
            if ($this->confirm('Remove SuperAuth service provider from config/app.php?', true)) {
                $content = str_replace(
                    "SuperAuth\\SuperAuthServiceProvider::class,",
                    '',
                    $content
                );
                
                File::put($configFile, $content);
                $this->line('  ✓ Removed service provider from config/app.php');
            }
        }
    }

    protected function displaySuccess()
    {
        $this->newLine();
        $this->line('╔══════════════════════════════════════════════════════════════╗');
        $this->line('║                    ✅ Removal Complete!                     ║');
        $this->line('║                                                              ║');
        $this->line('║  SuperAuth has been successfully removed from your project ║');
        $this->line('║                                                              ║');
        $this->line('║  What was removed:                                          ║');
        
        if (!$this->option('keep-data')) {
            $this->line('║  • Database tables and data                             ║');
        }
        
        if (!$this->option('keep-config')) {
            $this->line('║  • Configuration files                                  ║');
        }
        
        if (!$this->option('keep-views')) {
            $this->line('║  • Published view files                                 ║');
        }
        
        if (!$this->option('keep-assets')) {
            $this->line('║  • Published assets                                     ║');
        }
        
        $this->line('║  • Custom routes and middleware                             ║');
        $this->line('║  • Service provider registrations                          ║');
        $this->line('║                                                              ║');
        $this->line('║  Next Steps:                                                ║');
        $this->line('║  1. Run: composer remove superauth/superauth               ║');
        $this->line('║  2. Clear application cache: php artisan cache:clear       ║');
        $this->line('║  3. Clear config cache: php artisan config:clear         ║');
        $this->line('║  4. Clear route cache: php artisan route:clear             ║');
        $this->line('║                                                              ║');
        $this->line('║  Thank you for using SuperAuth! 🚀                         ║');
        $this->line('╚══════════════════════════════════════════════════════════════╝');
        $this->newLine();
    }
}
