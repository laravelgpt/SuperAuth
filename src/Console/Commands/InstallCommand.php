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

        // Show role selection options
        $roleOptions = [
            'default' => 'Default roles (Admin, User, Customer, Student)',
            'admin_focused' => 'Admin-focused (Super Admin, Admin, Moderator, User)',
            'business' => 'Business roles (Admin, Manager, Employee, Customer)',
            'education' => 'Education roles (Admin, Teacher, Student, Parent)',
            'custom' => 'Custom role selection',
            'all' => 'All available roles'
        ];

        $this->line('📋 Role configuration options:');
        foreach ($roleOptions as $key => $description) {
            $this->line("  • {$key}: {$description}");
        }
        $this->newLine();

        $choice = $this->choice(
            'Select role configuration:',
            array_keys($roleOptions),
            'default'
        );

        $this->configureRoleSelection($choice);

        $this->info('✅ Role configuration completed!');
        $this->newLine();
    }

    protected function configureRoleSelection($choice)
    {
        switch ($choice) {
            case 'default':
                $this->setupDefaultRoles();
                break;
            case 'admin_focused':
                $this->setupAdminFocusedRoles();
                break;
            case 'business':
                $this->setupBusinessRoles();
                break;
            case 'education':
                $this->setupEducationRoles();
                break;
            case 'custom':
                $this->setupCustomRoleSelection();
                break;
            case 'all':
                $this->setupAllRoles();
                break;
        }
    }

    protected function setupDefaultRoles()
    {
        $this->info('🔧 Setting up default roles...');
        $this->newLine();

        $defaultRoles = [
            'admin' => [
                'name' => 'Admin',
                'description' => 'System administrator with full access',
                'level' => 90
            ],
            'user' => [
                'name' => 'User',
                'description' => 'Regular user with basic access',
                'level' => 30
            ],
            'customer' => [
                'name' => 'Customer',
                'description' => 'Customer with purchase and profile access',
                'level' => 20
            ],
            'student' => [
                'name' => 'Student',
                'description' => 'Student with learning and profile access',
                'level' => 25
            ]
        ];

        $this->displayRoleTable($defaultRoles);
        $this->customRoles = array_values($defaultRoles);
    }

    protected function setupAdminFocusedRoles()
    {
        $this->info('👑 Setting up admin-focused roles...');
        $this->newLine();

        $adminRoles = [
            'super_admin' => [
                'name' => 'Super Admin',
                'description' => 'Super administrator with complete system control',
                'level' => 100
            ],
            'admin' => [
                'name' => 'Admin',
                'description' => 'Administrator with full system access',
                'level' => 90
            ],
            'moderator' => [
                'name' => 'Moderator',
                'description' => 'Content and user management',
                'level' => 60
            ],
            'user' => [
                'name' => 'User',
                'description' => 'Regular user with basic access',
                'level' => 30
            ]
        ];

        $this->displayRoleTable($adminRoles);
        $this->customRoles = array_values($adminRoles);
    }

    protected function setupBusinessRoles()
    {
        $this->info('💼 Setting up business roles...');
        $this->newLine();

        $businessRoles = [
            'admin' => [
                'name' => 'Admin',
                'description' => 'System administrator',
                'level' => 90
            ],
            'manager' => [
                'name' => 'Manager',
                'description' => 'Department manager with team access',
                'level' => 70
            ],
            'employee' => [
                'name' => 'Employee',
                'description' => 'Company employee with work access',
                'level' => 40
            ],
            'customer' => [
                'name' => 'Customer',
                'description' => 'External customer with limited access',
                'level' => 20
            ]
        ];

        $this->displayRoleTable($businessRoles);
        $this->customRoles = array_values($businessRoles);
    }

    protected function setupEducationRoles()
    {
        $this->info('🎓 Setting up education roles...');
        $this->newLine();

        $educationRoles = [
            'admin' => [
                'name' => 'Admin',
                'description' => 'School administrator',
                'level' => 90
            ],
            'teacher' => [
                'name' => 'Teacher',
                'description' => 'Teacher with class and student access',
                'level' => 60
            ],
            'student' => [
                'name' => 'Student',
                'description' => 'Student with learning access',
                'level' => 30
            ],
            'parent' => [
                'name' => 'Parent',
                'description' => 'Parent with child monitoring access',
                'level' => 25
            ]
        ];

        $this->displayRoleTable($educationRoles);
        $this->customRoles = array_values($educationRoles);
    }

    protected function setupCustomRoleSelection()
    {
        $this->info('🛠️ Custom role selection...');
        $this->newLine();

        $availableRoles = [
            'super_admin' => 'Super Admin - Complete system control',
            'admin' => 'Admin - Full system access',
            'moderator' => 'Moderator - Content and user management',
            'manager' => 'Manager - Department management',
            'teacher' => 'Teacher - Educational access',
            'employee' => 'Employee - Work access',
            'user' => 'User - Basic access',
            'customer' => 'Customer - Purchase access',
            'student' => 'Student - Learning access',
            'parent' => 'Parent - Child monitoring',
            'guest' => 'Guest - Limited access'
        ];

        $this->line('📋 Available roles to choose from:');
        foreach ($availableRoles as $key => $description) {
            $this->line("  • {$key}: {$description}");
        }
        $this->newLine();

        $selectedRoles = $this->ask('Enter role keys separated by commas (e.g., admin,user,customer)', 'admin,user');
        $roleKeys = array_map('trim', explode(',', $selectedRoles));

        $this->customRoles = [];
        foreach ($roleKeys as $key) {
            if (isset($availableRoles[$key])) {
                $this->customRoles[] = [
                    'name' => $key,
                    'description' => $availableRoles[$key],
                    'level' => $this->getRoleLevel($key)
                ];
            }
        }

        if (!empty($this->customRoles)) {
            $this->displayRoleTable(array_column($this->customRoles, null, 'name'));
        }
    }

    protected function setupAllRoles()
    {
        $this->info('🌟 Setting up all available roles...');
        $this->newLine();

        $allRoles = [
            'super_admin' => [
                'name' => 'Super Admin',
                'description' => 'Super administrator with complete system control',
                'level' => 100
            ],
            'admin' => [
                'name' => 'Admin',
                'description' => 'Administrator with full system access',
                'level' => 90
            ],
            'moderator' => [
                'name' => 'Moderator',
                'description' => 'Content and user management',
                'level' => 60
            ],
            'manager' => [
                'name' => 'Manager',
                'description' => 'Department manager',
                'level' => 70
            ],
            'teacher' => [
                'name' => 'Teacher',
                'description' => 'Educational access',
                'level' => 50
            ],
            'employee' => [
                'name' => 'Employee',
                'description' => 'Work access',
                'level' => 40
            ],
            'user' => [
                'name' => 'User',
                'description' => 'Basic access',
                'level' => 30
            ],
            'customer' => [
                'name' => 'Customer',
                'description' => 'Purchase access',
                'level' => 20
            ],
            'student' => [
                'name' => 'Student',
                'description' => 'Learning access',
                'level' => 25
            ],
            'parent' => [
                'name' => 'Parent',
                'description' => 'Child monitoring access',
                'level' => 15
            ],
            'guest' => [
                'name' => 'Guest',
                'description' => 'Limited access',
                'level' => 10
            ]
        ];

        $this->displayRoleTable($allRoles);
        $this->customRoles = array_values($allRoles);
    }

    protected function displayRoleTable($roles)
    {
        $this->table(
            ['Role', 'Description', 'Level'],
            collect($roles)->map(function ($role) {
                return [
                    $role['name'],
                    $role['description'],
                    $role['level']
                ];
            })->toArray()
        );
    }

    protected function getRoleLevel($roleKey)
    {
        $levels = [
            'super_admin' => 100,
            'admin' => 90,
            'moderator' => 60,
            'manager' => 70,
            'teacher' => 50,
            'employee' => 40,
            'user' => 30,
            'customer' => 20,
            'student' => 25,
            'parent' => 15,
            'guest' => 10
        ];

        return $levels[$roleKey] ?? 30;
    }


    protected function configureDashboard()
    {
        $this->info('🏠 Step 3: Configure Dashboard');
        $this->newLine();

        // Show dashboard options
        $dashboardOptions = [
            '/dashboard' => 'Standard dashboard (/dashboard)',
            '/admin' => 'Admin panel (/admin)',
            '/panel' => 'User panel (/panel)',
            '/app' => 'Application dashboard (/app)',
            '/home' => 'Home dashboard (/home)',
            'custom' => 'Custom URL (enter manually)'
        ];

        $this->line('📋 Available dashboard options:');
        foreach ($dashboardOptions as $url => $description) {
            $this->line("  • {$url}: {$description}");
        }
        $this->newLine();

        $choice = $this->choice(
            'Select dashboard URL or choose custom:',
            array_keys($dashboardOptions),
            '/dashboard'
        );

        if ($choice === 'custom') {
            $this->dashboardUrl = $this->askForCustomDashboardUrl();
        } else {
            $this->dashboardUrl = $choice;
        }

        // Validate dashboard URL
        $this->validateDashboardUrl();

        $this->info("✅ Dashboard URL set to: {$this->dashboardUrl}");
        $this->newLine();
    }

    protected function askForCustomDashboardUrl()
    {
        while (true) {
            $url = $this->ask('Enter custom dashboard URL (must start with /)', '/dashboard');
            
            if (empty($url)) {
                $this->error('Dashboard URL cannot be empty.');
                continue;
            }

            if (!str_starts_with($url, '/')) {
                $this->error('Dashboard URL must start with /.');
                continue;
            }

            if (strlen($url) < 2) {
                $this->error('Dashboard URL must be at least 2 characters long.');
                continue;
            }

            // Check for common conflicts
            $conflicts = ['/login', '/register', '/logout', '/api', '/admin', '/auth'];
            if (in_array($url, $conflicts)) {
                if (!$this->confirm("Warning: '{$url}' might conflict with existing routes. Continue anyway?", false)) {
                    continue;
                }
            }

            return $url;
        }
    }

    protected function validateDashboardUrl()
    {
        // Additional validation
        if (str_contains($this->dashboardUrl, ' ')) {
            $this->error('Dashboard URL cannot contain spaces.');
            $this->dashboardUrl = $this->ask('Enter a valid dashboard URL', '/dashboard');
        }

        if (str_contains($this->dashboardUrl, '..')) {
            $this->error('Dashboard URL cannot contain ".." for security reasons.');
            $this->dashboardUrl = $this->ask('Enter a valid dashboard URL', '/dashboard');
        }

        // Ensure it starts with /
        if (!str_starts_with($this->dashboardUrl, '/')) {
            $this->dashboardUrl = '/' . ltrim($this->dashboardUrl, '/');
        }
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
        $this->line('║  Dashboard Access:                                           ║');
        $this->line('║  • Main Dashboard: http://localhost:8000' . $this->dashboardUrl . '     ║');
        if ($this->adminEmail) {
            $this->line('║  • Login with: ' . $this->adminEmail . '                    ║');
        }
        $this->line('║                                                              ║');
        $this->line('║  Documentation: https://github.com/laravelgpt/SuperAuth      ║');
        $this->line('╚══════════════════════════════════════════════════════════════╝');
        $this->newLine();
    }
}
