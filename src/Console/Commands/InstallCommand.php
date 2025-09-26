<?php

namespace SuperAuth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use SuperAuth\Models\User;
use SuperAuth\Models\Role;

class InstallCommand extends Command
{
    protected $signature = 'superauth:install {--force : Force installation even if already installed}';
    protected $description = 'Install SuperAuth package with interactive setup';

    protected $selectedKit = null;
    protected $customRoles = [];
    protected $dashboardUrl = '/dashboard';
    protected $adminEmail = null;
    protected $adminPassword = null;

    public function handle()
    {
        $this->displayWelcome();
        
        if (!$this->option('force') && $this->isAlreadyInstalled()) {
            if (!$this->confirm('SuperAuth is already installed. Do you want to reinstall?', false)) {
                $this->info('Installation cancelled.');
                return;
            }
        }

        $this->info('🚀 Starting SuperAuth Installation...');
        $this->newLine();

        // Step 1: Framework Selection
        $this->selectFramework();

        // Step 2: Role Configuration
        $this->configureRoles();

        // Step 3: Dashboard Configuration
        $this->configureDashboard();

        // Step 4: Admin User Setup
        $this->setupAdminUser();

        // Step 5: Installation Process
        $this->runInstallation();

        // Step 6: Final Setup
        $this->finalizeInstallation();

        $this->displaySuccess();
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

    protected function displayWelcome()
    {
        $this->newLine();
        $this->line('╔══════════════════════════════════════════════════════════════╗');
        $this->line('║                    🚀 SuperAuth Installer                    ║');
        $this->line('║                                                              ║');
        $this->line('║  The Ultimate Laravel Authentication System                  ║');
        $this->line('║  with AI-Powered Security & Multi-Framework Support         ║');
        $this->line('║                                                              ║');
        $this->line('║  Features:                                                   ║');
        $this->line('║  • Multi-Provider Social Login (Google, Facebook, GitHub)    ║');
        $this->line('║  • OTP Authentication & Password Security                    ║');
        $this->line('║  • AI-Powered Monitoring & Notifications                    ║');
        $this->line('║  • Role-Based Access Control & Admin Dashboard             ║');
        $this->line('║  • Multi-Framework Support (Laravel, Livewire, Vue, React) ║');
        $this->line('║                                                              ║');
        $this->line('╚══════════════════════════════════════════════════════════════╝');
        $this->newLine();
    }

    protected function isAlreadyInstalled(): bool
    {
        try {
            return File::exists(config_path('superauth.php')) && 
                   \Illuminate\Support\Facades\Schema::hasTable('roles');
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function selectFramework()
    {
        $this->info('📱 Step 1: Select Your Frontend Framework');
        $this->newLine();

        $frameworks = [
            'laravel' => [
                'name' => 'Laravel Blade',
                'description' => 'Traditional server-side rendering with Blade templates',
                'icon' => '🔧',
                'features' => ['Server-side rendering', 'Form handling', 'Authentication views']
            ],
            'livewire' => [
                'name' => 'Livewire',
                'description' => 'Full-stack Laravel components with real-time updates',
                'icon' => '⚡',
                'features' => ['Real-time updates', 'Form validation', 'Authentication components']
            ],
            'vue' => [
                'name' => 'Vue.js',
                'description' => 'Progressive JavaScript framework with Composition API',
                'icon' => '🌐',
                'features' => ['Vue 3 components', 'Pinia state management', 'Vue Router']
            ],
            'react' => [
                'name' => 'React',
                'description' => 'JavaScript library with hooks and context',
                'icon' => '⚛️',
                'features' => ['React 18 components', 'Zustand state management', 'React Router']
            ],
            'nextjs' => [
                'name' => 'Next.js',
                'description' => 'React framework with server-side rendering',
                'icon' => '🚀',
                'features' => ['SSR/SSG', 'API routes', 'NextAuth.js integration']
            ]
        ];

        $this->table(
            ['Option', 'Framework', 'Description', 'Features'],
            collect($frameworks)->map(function ($framework, $key) {
                return [
                    $key,
                    $framework['icon'] . ' ' . $framework['name'],
                    $framework['description'],
                    implode(', ', $framework['features'])
                ];
            })->toArray()
        );

        $this->newLine();
        $choice = $this->choice(
            'Which framework would you like to use?',
            array_keys($frameworks),
            'laravel'
        );

        $this->selectedKit = $choice;
        $this->info("✅ Selected: {$frameworks[$choice]['name']}");
        $this->newLine();
    }

    protected function configureRoles()
    {
        $this->info('👥 Step 2: Configure User Roles');
        $this->newLine();

        // Default roles
        $defaultRoles = [
            'admin' => 'Administrator - Full system access',
            'moderator' => 'Moderator - Content and user management',
            'user' => 'Regular User - Basic access',
            'guest' => 'Guest - Limited access'
        ];

        $this->line('📋 Default roles that will be created:');
        foreach ($defaultRoles as $role => $description) {
            $this->line("  • {$role}: {$description}");
        }
        $this->newLine();

        if ($this->confirm('Do you want to add custom roles?', false)) {
            $this->addCustomRoles();
        }

        $this->info('✅ Role configuration completed!');
        $this->newLine();
    }

    protected function addCustomRoles()
    {
        $this->info('➕ Adding Custom Roles');
        $this->newLine();

        while (true) {
            $roleName = $this->ask('Enter custom role name (or press Enter to finish)', '');
            
            if (empty($roleName)) {
                break;
            }

            $roleDescription = $this->ask('Enter role description', 'Custom role');
            $roleLevel = $this->ask('Enter role level (0-100, higher = more permissions)', 50);

            $this->customRoles[] = [
                'name' => $roleName,
                'description' => $roleDescription,
                'level' => (int) $roleLevel
            ];

            $this->info("✅ Added custom role: {$roleName}");
            $this->newLine();
        }
    }

    protected function configureDashboard()
    {
        $this->info('🏠 Step 3: Configure Dashboard');
        $this->newLine();

        $this->dashboardUrl = $this->ask('Enter dashboard URL', '/dashboard');
        
        $this->info("✅ Dashboard URL set to: {$this->dashboardUrl}");
        $this->newLine();
    }

    protected function setupAdminUser()
    {
        $this->info('👤 Step 4: Setup Admin User');
        $this->newLine();

        if ($this->confirm('Do you want to create an admin user now?', true)) {
            $this->adminEmail = $this->ask('Admin email address', 'admin@example.com');
            $this->adminPassword = $this->secret('Admin password (min 8 characters)');
            
            if (strlen($this->adminPassword) < 8) {
                $this->error('Password must be at least 8 characters long.');
                $this->adminPassword = $this->secret('Admin password (min 8 characters)');
            }

            $this->info('✅ Admin user configuration completed!');
        }
        $this->newLine();
    }

    protected function runInstallation()
    {
        $this->info('⚙️ Step 5: Running Installation...');
        $this->newLine();

        $steps = [
            'Publishing configuration...' => fn() => $this->publishConfiguration(),
            'Publishing migrations...' => fn() => $this->publishMigrations(),
            'Publishing views...' => fn() => $this->publishViews(),
            'Publishing assets...' => fn() => $this->publishAssets(),
            'Running migrations...' => fn() => $this->runMigrations(),
            'Creating roles and permissions...' => fn() => $this->createDefaultRoles(),
            'Installing framework kit...' => fn() => $this->installFrameworkKit(),
        ];

        $progressBar = $this->output->createProgressBar(count($steps));
        $progressBar->start();

        foreach ($steps as $step => $callback) {
            $this->line("  {$step}");
            $callback();
            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);
    }

    protected function installFrameworkKit()
    {
        $kitCommands = [
            'laravel' => 'superauth:install-laravel-kit',
            'livewire' => 'superauth:install-livewire-kit',
            'vue' => 'superauth:install-vue-kit',
            'react' => 'superauth:install-react-kit',
            'nextjs' => 'superauth:install-react-kit --nextjs'
        ];

        if (isset($kitCommands[$this->selectedKit])) {
            Artisan::call($kitCommands[$this->selectedKit]);
        }
    }

    protected function finalizeInstallation()
    {
        $this->info('🎯 Step 6: Finalizing Installation...');
        $this->newLine();

        // Create admin user if configured
        if ($this->adminEmail && $this->adminPassword) {
            $this->createAdminUser();
        }

        // Create custom roles
        $this->createCustomRoles();

        // Update dashboard configuration
        $this->updateDashboardConfig();
    }

    protected function createAdminUser()
    {
        try {
            $user = User::create([
                'name' => 'Super Admin',
                'email' => $this->adminEmail,
                'password' => bcrypt($this->adminPassword),
                'email_verified_at' => now(),
            ]);

            $user->assignRole('admin');
            $this->info("✅ Admin user created: {$this->adminEmail}");
        } catch (\Exception $e) {
            $this->error("❌ Failed to create admin user: {$e->getMessage()}");
        }
    }

    protected function createCustomRoles()
    {
        foreach ($this->customRoles as $roleData) {
            try {
                Role::create([
                    'name' => $roleData['name'],
                    'guard_name' => 'web',
                    'description' => $roleData['description'],
                    'level' => $roleData['level'],
                ]);
                $this->info("✅ Custom role created: {$roleData['name']}");
            } catch (\Exception $e) {
                $this->error("❌ Failed to create role {$roleData['name']}: {$e->getMessage()}");
            }
        }
    }

    protected function updateDashboardConfig()
    {
        // Update configuration with dashboard URL
        $configPath = config_path('superauth.php');
        if (File::exists($configPath)) {
            $config = File::get($configPath);
            $config = str_replace("'prefix' => env('SUPERAUTH_ROUTES_PREFIX', '')", "'prefix' => env('SUPERAUTH_ROUTES_PREFIX', '{$this->dashboardUrl}')", $config);
            File::put($configPath, $config);
        }
    }

    protected function displaySuccess()
    {
        $this->newLine();
        $this->line('╔══════════════════════════════════════════════════════════════╗');
        $this->line('║                    🎉 Installation Complete!                 ║');
        $this->line('║                                                              ║');
        $this->line('║  SuperAuth has been successfully installed with:            ║');
        $this->line("║  • Framework: {$this->selectedKit}                                    ║");
        $this->line("║  • Dashboard: {$this->dashboardUrl}                              ║");
        $this->line('║  • Roles: Admin, User' . (count($this->customRoles) > 0 ? ', ' . count($this->customRoles) . ' custom' : '') . ' roles created ║');
        if ($this->adminEmail) {
            $this->line("║  • Admin User: {$this->adminEmail}                        ║");
        }
        $this->line('║                                                              ║');
        $this->line('║  Next Steps:                                                 ║');
        $this->line('║  1. Configure your .env file with database settings          ║');
        $this->line('║  2. Run: php artisan serve                                   ║');
        $this->line('║  3. Visit: http://localhost:8000' . $this->dashboardUrl . '           ║');
        $this->line('║                                                              ║');
        $this->line('║  Documentation: https://github.com/laravelgpt/SuperAuth      ║');
        $this->line('╚══════════════════════════════════════════════════════════════╝');
        $this->newLine();
    }
}
