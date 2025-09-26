<?php

namespace SuperAuth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FixArtisanCommand extends Command
{
    protected $signature = 'superauth:fix-artisan';
    protected $description = 'Fix Laravel artisan file for Laravel 11+ compatibility';

    public function handle()
    {
        $this->info('🔧 Fixing Laravel artisan file for Laravel 11+ compatibility...');
        $this->newLine();

        $artisanPath = base_path('artisan');
        
        if (!File::exists($artisanPath)) {
            $this->error('❌ Artisan file not found at: ' . $artisanPath);
            return 1;
        }

        // Read current artisan file
        $currentContent = File::get($artisanPath);
        
        // Check if it's already the correct version
        if (str_contains($currentContent, 'Illuminate\Contracts\Console\Kernel::class')) {
            $this->info('✅ Artisan file is already compatible with Laravel 11+');
            return 0;
        }

        // Create the correct artisan file content
        $correctContent = $this->getCorrectArtisanContent();
        
        // Backup the old file
        $backupPath = $artisanPath . '.backup.' . date('Y-m-d-H-i-s');
        File::put($backupPath, $currentContent);
        $this->line("📁 Created backup: " . basename($backupPath));

        // Write the correct content
        File::put($artisanPath, $correctContent);
        $this->line("✅ Updated artisan file");

        // Make it executable
        if (PHP_OS_FAMILY !== 'Windows') {
            chmod($artisanPath, 0755);
        }

        $this->info('🎉 Artisan file has been fixed for Laravel 11+ compatibility!');
        $this->newLine();
        
        $this->line('📋 What was fixed:');
        $this->line('  • Removed old handleCommand() method call');
        $this->line('  • Added proper Console Kernel instantiation');
        $this->line('  • Fixed input/output handling');
        $this->line('  • Added proper error handling and exit codes');
        
        $this->newLine();
        $this->line('🔄 You can now run: php artisan package:discover');
        
        return 0;
    }

    protected function getCorrectArtisanContent()
    {
        return '#!/usr/bin/env php
<?php

define(\'LARAVEL_START\', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We\'ll require it
| into the script here so that we do not have to worry about the
| loading of any our classes "manually". Feels great to relax.
|
*/

require __DIR__.\'/vendor/autoload.php\';

$app = require_once __DIR__.\'/bootstrap/app.php\';

/*
|--------------------------------------------------------------------------
| Run The Artisan Application
|--------------------------------------------------------------------------
|
| When we run the console application, the current CLI command will be
| executed in this console and the response sent back to a terminal
| or another output device for the developers. Here goes nothing!
|
*/

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$status = $kernel->handle(
    $input = new Symfony\Component\Console\Input\ArgvInput,
    new Symfony\Component\Console\Output\ConsoleOutput
);

/*
|--------------------------------------------------------------------------
| Shutdown The Application
|--------------------------------------------------------------------------
|
| Once Artisan has finished running, we will fire off the shutdown events
| so that any final work may be done by the application before we shut
| down the process. This is the last thing to happen to the request.
|
*/

$kernel->terminate($input, $status);

exit($status);
';
    }
}
