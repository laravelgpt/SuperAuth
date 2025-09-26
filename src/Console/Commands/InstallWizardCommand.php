<?php

namespace SuperAuth\Console\Commands;

use Illuminate\Console\Command;
use SuperAuth\Core\FeatureManager;
use SuperAuth\Core\ConfigurationManager;
use SuperAuth\Core\ThemeManager;

class InstallWizardCommand extends Command
{
    protected $signature = 'superauth:install-wizard {--force : Force installation even if already installed}';
    protected $description = 'Interactive SuperAuth package installation wizard';

    protected $featureManager;
    protected $configManager;
    protected $themeManager;

    public function __construct(FeatureManager $featureManager, ConfigurationManager $configManager, ThemeManager $themeManager)
    {
        parent::__construct();
        $this->featureManager = $featureManager;
        $this->configManager = $configManager;
        $this->themeManager = $themeManager;
    }

    public function handle()
    {
        $this->info('🚀 Welcome to SuperAuth Installation Wizard!');
        
        // Step 1: Feature Selection
        $this->selectFeatures();
        
        // Step 2: Theme Selection
        $this->selectTheme();
        
        // Step 3: Database Setup
        $this->setupDatabase();
        
        // Step 4: Admin User Creation
        $this->createAdminUser();
        
        $this->info('✅ SuperAuth package installed successfully!');
    }

    protected function selectFeatures()
    {
        $this->info('🎯 Feature Selection');
        $features = $this->featureManager->getAllFeatures();
        
        foreach ($features as $feature => $config) {
            $enabled = $this->confirm("Enable {$config['name']} feature?", $config['enabled']);
            if ($enabled) {
                $this->featureManager->enable($feature);
            } else {
                $this->featureManager->disable($feature);
            }
        }
    }

    protected function selectTheme()
    {
        $this->info('🎨 Theme Selection');
        $themes = $this->themeManager->getAvailableThemes();
        $themeOptions = [];
        
        foreach ($themes as $key => $theme) {
            $themeOptions[$key] = "{$theme['name']} - {$theme['description']}";
        }
        
        $selectedTheme = $this->choice('Select a theme:', $themeOptions, 'glass-morphism');
        $this->themeManager->setCurrentTheme($selectedTheme);
    }

    protected function setupDatabase()
    {
        $this->info('🗄️ Database Setup');
        
        if ($this->confirm('Run database migrations?', true)) {
            $this->call('migrate', ['--force' => true]);
        }
        
        if ($this->confirm('Create default roles and permissions?', true)) {
            $this->call('superauth:create-default-roles');
        }
    }

    protected function createAdminUser()
    {
        $this->info('👤 Admin User Creation');
        
        if ($this->confirm('Create admin user?', true)) {
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
                $this->line('✅ Admin user created successfully!');
            }
        }
    }
}