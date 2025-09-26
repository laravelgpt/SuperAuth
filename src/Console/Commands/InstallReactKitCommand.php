<?php

namespace SuperAuth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class InstallReactKitCommand extends Command
{
    protected $signature = 'superauth:install-react-kit 
                            {--force : Force installation even if files exist}
                            {--with-demo : Include demo components}
                            {--nextjs : Install Next.js specific components}';

    protected $description = 'Install SuperAuth React/Next.js kit with components and views';

    public function handle()
    {
        $this->info('🚀 Installing SuperAuth React/Next.js Kit...');

        $this->installReactComponents();
        $this->installReactPages();
        $this->installReactHooks();
        $this->installReactContext();
        $this->installReactServices();
        
        if ($this->option('with-demo')) {
            $this->installDemoComponents();
        }

        if ($this->option('nextjs')) {
            $this->installNextJsComponents();
        }

        $this->publishAssets();
        $this->updateRoutes();
        $this->updateConfig();
        $this->updatePackageJson();

        $this->info('✅ React/Next.js Kit installed successfully!');
        $this->comment('Run `npm install` to install dependencies.');
        $this->comment('Run `npm run dev` to start development server.');
    }

    protected function installReactComponents()
    {
        $this->info('Installing React components...');

        $components = [
            'Auth/LoginForm.jsx' => $this->getLoginFormComponent(),
            'Auth/RegisterForm.jsx' => $this->getRegisterFormComponent(),
            'Auth/ForgotPasswordForm.jsx' => $this->getForgotPasswordFormComponent(),
            'Auth/ResetPasswordForm.jsx' => $this->getResetPasswordFormComponent(),
            'Auth/SocialLogin.jsx' => $this->getSocialLoginComponent(),
            'Profile/UserProfile.jsx' => $this->getUserProfileComponent(),
            'Profile/EditProfile.jsx' => $this->getEditProfileComponent(),
            'Admin/Dashboard.jsx' => $this->getAdminDashboardComponent(),
            'Admin/UserManagement.jsx' => $this->getUserManagementComponent(),
            'Admin/RoleManagement.jsx' => $this->getRoleManagementComponent(),
            'Security/PasswordStrength.jsx' => $this->getPasswordStrengthComponent(),
            'Security/BreachCheck.jsx' => $this->getBreachCheckComponent(),
        ];

        foreach ($components as $path => $content) {
            $fullPath = resource_path("assets/js/react/components/{$path}");
            $this->createDirectory(dirname($fullPath));
            File::put($fullPath, $content);
            $this->line("  ✓ Created: {$path}");
        }
    }

    protected function installReactPages()
    {
        $this->info('Installing React pages...');

        $pages = [
            'auth/login.jsx' => $this->getLoginPage(),
            'auth/register.jsx' => $this->getRegisterPage(),
            'auth/forgot-password.jsx' => $this->getForgotPasswordPage(),
            'auth/reset-password.jsx' => $this->getResetPasswordPage(),
            'auth/social-login.jsx' => $this->getSocialLoginPage(),
            'profile/profile.jsx' => $this->getProfilePage(),
            'admin/dashboard.jsx' => $this->getAdminDashboardPage(),
            'admin/users.jsx' => $this->getAdminUsersPage(),
            'admin/roles.jsx' => $this->getAdminRolesPage(),
            'user/dashboard.jsx' => $this->getUserDashboardPage(),
            'user/profile.jsx' => $this->getUserProfilePage(),
        ];

        foreach ($pages as $file => $content) {
            $path = resource_path("assets/js/react/pages/{$file}");
            $this->createDirectory(dirname($path));
            File::put($path, $content);
            $this->line("  ✓ Created: {$file}");
        }
    }

    protected function installReactHooks()
    {
        $this->info('Installing React hooks...');

        $hooks = [
            'useAuth.js' => $this->getUseAuthHook(),
            'useUser.js' => $this->getUseUserHook(),
            'useSecurity.js' => $this->getUseSecurityHook(),
            'useNotifications.js' => $this->getUseNotificationsHook(),
            'useApi.js' => $this->getUseApiHook(),
        ];

        foreach ($hooks as $file => $content) {
            $path = resource_path("assets/js/react/hooks/{$file}");
            $this->createDirectory(dirname($path));
            File::put($path, $content);
            $this->line("  ✓ Created: {$file}");
        }
    }

    protected function installReactContext()
    {
        $this->info('Installing React context...');

        $contexts = [
            'AuthContext.jsx' => $this->getAuthContext(),
            'UserContext.jsx' => $this->getUserContext(),
            'SecurityContext.jsx' => $this->getSecurityContext(),
            'NotificationContext.jsx' => $this->getNotificationContext(),
        ];

        foreach ($contexts as $file => $content) {
            $path = resource_path("assets/js/react/context/{$file}");
            $this->createDirectory(dirname($path));
            File::put($path, $content);
            $this->line("  ✓ Created: {$file}");
        }
    }

    protected function installReactServices()
    {
        $this->info('Installing React services...');

        $services = [
            'api.js' => $this->getApiService(),
            'auth.js' => $this->getAuthService(),
            'user.js' => $this->getUserService(),
            'security.js' => $this->getSecurityService(),
            'notifications.js' => $this->getNotificationService(),
        ];

        foreach ($services as $file => $content) {
            $path = resource_path("assets/js/react/services/{$file}");
            $this->createDirectory(dirname($path));
            File::put($path, $content);
            $this->line("  ✓ Created: {$file}");
        }
    }

    protected function installNextJsComponents()
    {
        $this->info('Installing Next.js specific components...');

        $nextjsComponents = [
            'pages/_app.js' => $this->getNextJsApp(),
            'pages/_document.js' => $this->getNextJsDocument(),
            'pages/api/auth/[...nextauth].js' => $this->getNextAuthApi(),
            'pages/api/auth/login.js' => $this->getLoginApi(),
            'pages/api/auth/register.js' => $this->getRegisterApi(),
            'pages/api/auth/logout.js' => $this->getLogoutApi(),
            'pages/api/user/profile.js' => $this->getUserProfileApi(),
            'pages/api/admin/users.js' => $this->getAdminUsersApi(),
            'pages/api/admin/roles.js' => $this->getAdminRolesApi(),
        ];

        foreach ($nextjsComponents as $file => $content) {
            $path = resource_path("assets/js/nextjs/{$file}");
            $this->createDirectory(dirname($path));
            File::put($path, $content);
            $this->line("  ✓ Created: {$file}");
        }
    }

    protected function installDemoComponents()
    {
        $this->info('Installing demo components...');

        $demos = [
            'Demo/LandingPage.jsx' => $this->getLandingPageDemo(),
            'Demo/Features.jsx' => $this->getFeaturesDemo(),
            'Demo/Pricing.jsx' => $this->getPricingDemo(),
        ];

        foreach ($demos as $file => $content) {
            $path = resource_path("assets/js/react/components/{$file}");
            $this->createDirectory(dirname($path));
            File::put($path, $content);
            $this->line("  ✓ Created: {$file}");
        }
    }

    protected function publishAssets()
    {
        $this->info('Publishing assets...');
        
        Artisan::call('vendor:publish', [
            '--tag' => 'superauth-views',
            '--force' => $this->option('force')
        ]);
        
        Artisan::call('vendor:publish', [
            '--tag' => 'superauth-assets',
            '--force' => $this->option('force')
        ]);
    }

    protected function updateRoutes()
    {
        $this->info('Updating routes...');
        
        $routesContent = $this->getReactRoutes();
        $routesPath = base_path('routes/web.php');
        
        if (!File::exists($routesPath) || $this->option('force')) {
            File::put($routesPath, $routesContent);
            $this->line("  ✓ Updated: routes/web.php");
        }
    }

    protected function updateConfig()
    {
        $this->info('Updating configuration...');
        
        $configContent = $this->getReactConfig();
        $configPath = config_path('superauth.php');
        
        if (!File::exists($configPath) || $this->option('force')) {
            File::put($configPath, $configContent);
            $this->line("  ✓ Updated: config/superauth.php");
        }
    }

    protected function updatePackageJson()
    {
        $this->info('Updating package.json...');
        
        $packageJsonPath = base_path('package.json');
        
        if (File::exists($packageJsonPath)) {
            $packageJson = json_decode(File::get($packageJsonPath), true);
            
            $packageJson['dependencies'] = array_merge($packageJson['dependencies'] ?? [], [
                'react' => '^18.2.0',
                'react-dom' => '^18.2.0',
                'react-router-dom' => '^6.14.0',
                'axios' => '^1.4.0',
                'zustand' => '^4.3.0',
            ]);
            
            $packageJson['devDependencies'] = array_merge($packageJson['devDependencies'] ?? [], [
                '@vitejs/plugin-react' => '^4.0.0',
                'vite' => '^4.4.0',
                'eslint' => '^8.45.0',
                'eslint-plugin-react' => '^7.33.0',
                'eslint-plugin-react-hooks' => '^4.6.0',
            ]);
            
            if ($this->option('nextjs')) {
                $packageJson['dependencies'] = array_merge($packageJson['dependencies'], [
                    'next' => '^13.4.0',
                    'next-auth' => '^4.22.0',
                ]);
            }
            
            File::put($packageJsonPath, json_encode($packageJson, JSON_PRETTY_PRINT));
            $this->line("  ✓ Updated: package.json");
        }
    }

    protected function createDirectory($path)
    {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }

    // React Component methods
    protected function getLoginFormComponent()
    {
        return 'import React, { useState } from "react";
import { useAuth } from "../../hooks/useAuth";
import { useRouter } from "react-router-dom";

const LoginForm = () => {
  const [form, setForm] = useState({
    email: "",
    password: "",
  });
  const [loading, setLoading] = useState(false);
  
  const { login } = useAuth();
  const router = useRouter();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    
    try {
      await login(form);
      router.push("/dashboard");
    } catch (error) {
      console.error("Login failed:", error);
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (e) => {
    setForm({
      ...form,
      [e.target.name]: e.target.value,
    });
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-100">
      <div className="max-w-md w-full space-y-8">
        <div className="text-center">
          <h2 className="mt-6 text-3xl font-extrabold text-gray-900">
            Sign in to your account
          </h2>
        </div>
        
        <form onSubmit={handleSubmit} className="mt-8 space-y-6">
          <div>
            <label htmlFor="email" className="block text-sm font-medium text-gray-700">
              Email Address
            </label>
            <input
              id="email"
              name="email"
              type="email"
              required
              value={form.email}
              onChange={handleChange}
              className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
            />
          </div>
          
          <div>
            <label htmlFor="password" className="block text-sm font-medium text-gray-700">
              Password
            </label>
            <input
              id="password"
              name="password"
              type="password"
              required
              value={form.password}
              onChange={handleChange}
              className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
            />
          </div>
          
          <div>
            <button
              type="submit"
              disabled={loading}
              className="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 disabled:opacity-50"
            >
              {loading ? "Signing in..." : "Sign in"}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default LoginForm;';
    }

    protected function getRegisterFormComponent()
    {
        return 'import React, { useState } from "react";
import { useAuth } from "../../hooks/useAuth";
import { useRouter } from "react-router-dom";

const RegisterForm = () => {
  const [form, setForm] = useState({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
  });
  const [loading, setLoading] = useState(false);
  
  const { register } = useAuth();
  const router = useRouter();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    
    try {
      await register(form);
      router.push("/dashboard");
    } catch (error) {
      console.error("Registration failed:", error);
    } finally {
      setLoading(false);
    }
  };

  const handleChange = (e) => {
    setForm({
      ...form,
      [e.target.name]: e.target.value,
    });
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-100">
      <div className="max-w-md w-full space-y-8">
        <div className="text-center">
          <h2 className="mt-6 text-3xl font-extrabold text-gray-900">
            Create your account
          </h2>
        </div>
        
        <form onSubmit={handleSubmit} className="mt-8 space-y-6">
          <div>
            <label htmlFor="name" className="block text-sm font-medium text-gray-700">
              Full Name
            </label>
            <input
              id="name"
              name="name"
              type="text"
              required
              value={form.name}
              onChange={handleChange}
              className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
            />
          </div>
          
          <div>
            <label htmlFor="email" className="block text-sm font-medium text-gray-700">
              Email Address
            </label>
            <input
              id="email"
              name="email"
              type="email"
              required
              value={form.email}
              onChange={handleChange}
              className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
            />
          </div>
          
          <div>
            <label htmlFor="password" className="block text-sm font-medium text-gray-700">
              Password
            </label>
            <input
              id="password"
              name="password"
              type="password"
              required
              value={form.password}
              onChange={handleChange}
              className="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
            />
          </div>
          
          <div>
            <button
              type="submit"
              disabled={loading}
              className="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 disabled:opacity-50"
            >
              {loading ? "Creating account..." : "Create Account"}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
};

export default RegisterForm;';
    }

    // Additional component methods
    protected function getForgotPasswordFormComponent() { return '<!-- Forgot Password Form Component -->'; }
    protected function getResetPasswordFormComponent() { return '<!-- Reset Password Form Component -->'; }
    protected function getSocialLoginComponent() { return '<!-- Social Login Component -->'; }
    protected function getUserProfileComponent() { return '<!-- User Profile Component -->'; }
    protected function getEditProfileComponent() { return '<!-- Edit Profile Component -->'; }
    protected function getAdminDashboardComponent() { return '<!-- Admin Dashboard Component -->'; }
    protected function getUserManagementComponent() { return '<!-- User Management Component -->'; }
    protected function getRoleManagementComponent() { return '<!-- Role Management Component -->'; }
    protected function getPasswordStrengthComponent() { return '<!-- Password Strength Component -->'; }
    protected function getBreachCheckComponent() { return '<!-- Breach Check Component -->'; }

    // Page methods
    protected function getLoginPage() { return '<!-- Login Page -->'; }
    protected function getRegisterPage() { return '<!-- Register Page -->'; }
    protected function getForgotPasswordPage() { return '<!-- Forgot Password Page -->'; }
    protected function getResetPasswordPage() { return '<!-- Reset Password Page -->'; }
    protected function getSocialLoginPage() { return '<!-- Social Login Page -->'; }
    protected function getProfilePage() { return '<!-- Profile Page -->'; }
    protected function getAdminDashboardPage() { return '<!-- Admin Dashboard Page -->'; }
    protected function getAdminUsersPage() { return '<!-- Admin Users Page -->'; }
    protected function getAdminRolesPage() { return '<!-- Admin Roles Page -->'; }
    protected function getUserDashboardPage() { return '<!-- User Dashboard Page -->'; }
    protected function getUserProfilePage() { return '<!-- User Profile Page -->'; }

    // Hook methods
    protected function getUseAuthHook()
    {
        return 'import { useState, useEffect } from "react";
import { useAuthStore } from "../stores/auth";

export const useAuth = () => {
  const [loading, setLoading] = useState(false);
  const authStore = useAuthStore();

  const login = async (credentials) => {
    setLoading(true);
    try {
      await authStore.login(credentials);
    } catch (error) {
      throw error;
    } finally {
      setLoading(false);
    }
  };

  const register = async (userData) => {
    setLoading(true);
    try {
      await authStore.register(userData);
    } catch (error) {
      throw error;
    } finally {
      setLoading(false);
    }
  };

  const logout = async () => {
    setLoading(true);
    try {
      await authStore.logout();
    } catch (error) {
      throw error;
    } finally {
      setLoading(false);
    }
  };

  return {
    user: authStore.user,
    isAuthenticated: authStore.isAuthenticated,
    login,
    register,
    logout,
    loading,
  };
};';
    }

    protected function getUseUserHook() { return '<!-- Use User Hook -->'; }
    protected function getUseSecurityHook() { return '<!-- Use Security Hook -->'; }
    protected function getUseNotificationsHook() { return '<!-- Use Notifications Hook -->'; }
    protected function getUseApiHook() { return '<!-- Use API Hook -->'; }

    // Context methods
    protected function getAuthContext() { return '<!-- Auth Context -->'; }
    protected function getUserContext() { return '<!-- User Context -->'; }
    protected function getSecurityContext() { return '<!-- Security Context -->'; }
    protected function getNotificationContext() { return '<!-- Notification Context -->'; }

    // Service methods
    protected function getApiService() { return '<!-- API Service -->'; }
    protected function getAuthService() { return '<!-- Auth Service -->'; }
    protected function getUserService() { return '<!-- User Service -->'; }
    protected function getSecurityService() { return '<!-- Security Service -->'; }
    protected function getNotificationService() { return '<!-- Notification Service -->'; }

    // Next.js methods
    protected function getNextJsApp() { return '<!-- Next.js App -->'; }
    protected function getNextJsDocument() { return '<!-- Next.js Document -->'; }
    protected function getNextAuthApi() { return '<!-- NextAuth API -->'; }
    protected function getLoginApi() { return '<!-- Login API -->'; }
    protected function getRegisterApi() { return '<!-- Register API -->'; }
    protected function getLogoutApi() { return '<!-- Logout API -->'; }
    protected function getUserProfileApi() { return '<!-- User Profile API -->'; }
    protected function getAdminUsersApi() { return '<!-- Admin Users API -->'; }
    protected function getAdminRolesApi() { return '<!-- Admin Roles API -->'; }

    // Demo methods
    protected function getLandingPageDemo() { return '<!-- Landing Page Demo -->'; }
    protected function getFeaturesDemo() { return '<!-- Features Demo -->'; }
    protected function getPricingDemo() { return '<!-- Pricing Demo -->'; }

    // Configuration methods
    protected function getReactRoutes()
    {
        return '<?php

use Illuminate\Support\Facades\Route;

// React SPA Routes
Route::get("/{any}", function () {
    return view("react.app");
})->where("any", ".*");';
    }

    protected function getReactConfig()
    {
        return '<?php

return [
    "features" => [
        "authentication" => [
            "enabled" => true,
            "methods" => [
                "traditional" => true,
                "social" => true,
                "otp" => false,
            ],
        ],
        "authorization" => [
            "enabled" => true,
            "roles" => [
                "default_user_role" => "user",
                "default_admin_role" => "admin",
            ],
        ],
        "security" => [
            "enabled" => true,
            "password_breach_checking" => true,
            "password_strength_analysis" => true,
        ],
    ],
    "react" => [
        "enabled" => true,
        "spa" => true,
        "nextjs" => false,
        "components" => [
            "auth" => true,
            "profile" => true,
            "admin" => true,
        ],
    ],
];';
    }
}
