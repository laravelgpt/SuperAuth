<?php

namespace SuperAuth\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class InstallVueKitCommand extends Command
{
    protected $signature = 'superauth:install-vue-kit 
                            {--force : Force installation even if files exist}
                            {--with-demo : Include demo components}';

    protected $description = 'Install SuperAuth Vue.js kit with components and views';

    public function handle()
    {
        $this->info('🚀 Installing SuperAuth Vue.js Kit...');

        $this->installVueComponents();
        $this->installVueViews();
        $this->installVueStores();
        $this->installVueComposables();
        
        if ($this->option('with-demo')) {
            $this->installDemoComponents();
        }

        $this->publishAssets();
        $this->updateRoutes();
        $this->updateConfig();
        $this->updatePackageJson();

        $this->info('✅ Vue.js Kit installed successfully!');
        $this->comment('Run `npm install` to install dependencies.');
        $this->comment('Run `npm run dev` to start development server.');
    }

    protected function installVueComponents()
    {
        $this->info('Installing Vue.js components...');

        $components = [
            'Auth/LoginForm.vue' => $this->getLoginFormComponent(),
            'Auth/RegisterForm.vue' => $this->getRegisterFormComponent(),
            'Auth/ForgotPasswordForm.vue' => $this->getForgotPasswordFormComponent(),
            'Auth/ResetPasswordForm.vue' => $this->getResetPasswordFormComponent(),
            'Auth/SocialLogin.vue' => $this->getSocialLoginComponent(),
            'Profile/UserProfile.vue' => $this->getUserProfileComponent(),
            'Profile/EditProfile.vue' => $this->getEditProfileComponent(),
            'Admin/Dashboard.vue' => $this->getAdminDashboardComponent(),
            'Admin/UserManagement.vue' => $this->getUserManagementComponent(),
            'Admin/RoleManagement.vue' => $this->getRoleManagementComponent(),
            'Security/PasswordStrength.vue' => $this->getPasswordStrengthComponent(),
            'Security/BreachCheck.vue' => $this->getBreachCheckComponent(),
        ];

        foreach ($components as $path => $content) {
            $fullPath = resource_path("assets/js/vue/components/{$path}");
            $this->createDirectory(dirname($fullPath));
            File::put($fullPath, $content);
            $this->line("  ✓ Created: {$path}");
        }
    }

    protected function installVueViews()
    {
        $this->info('Installing Vue.js views...');

        $views = [
            'auth/login.vue' => $this->getLoginView(),
            'auth/register.vue' => $this->getRegisterView(),
            'auth/forgot-password.vue' => $this->getForgotPasswordView(),
            'auth/reset-password.vue' => $this->getResetPasswordView(),
            'auth/social-login.vue' => $this->getSocialLoginView(),
            'profile/profile.vue' => $this->getProfileView(),
            'admin/dashboard.vue' => $this->getAdminDashboardView(),
            'admin/users.vue' => $this->getAdminUsersView(),
            'admin/roles.vue' => $this->getAdminRolesView(),
            'user/dashboard.vue' => $this->getUserDashboardView(),
            'user/profile.vue' => $this->getUserProfileView(),
        ];

        foreach ($views as $file => $content) {
            $path = resource_path("assets/js/vue/views/{$file}");
            $this->createDirectory(dirname($path));
            File::put($path, $content);
            $this->line("  ✓ Created: {$file}");
        }
    }

    protected function installVueStores()
    {
        $this->info('Installing Vue.js stores...');

        $stores = [
            'auth.js' => $this->getAuthStore(),
            'user.js' => $this->getUserStore(),
            'admin.js' => $this->getAdminStore(),
            'security.js' => $this->getSecurityStore(),
        ];

        foreach ($stores as $file => $content) {
            $path = resource_path("assets/js/vue/stores/{$file}");
            $this->createDirectory(dirname($path));
            File::put($path, $content);
            $this->line("  ✓ Created: {$file}");
        }
    }

    protected function installVueComposables()
    {
        $this->info('Installing Vue.js composables...');

        $composables = [
            'useAuth.js' => $this->getUseAuthComposable(),
            'useUser.js' => $this->getUseUserComposable(),
            'useSecurity.js' => $this->getUseSecurityComposable(),
            'useNotifications.js' => $this->getUseNotificationsComposable(),
        ];

        foreach ($composables as $file => $content) {
            $path = resource_path("assets/js/vue/composables/{$file}");
            $this->createDirectory(dirname($path));
            File::put($path, $content);
            $this->line("  ✓ Created: {$file}");
        }
    }

    protected function installDemoComponents()
    {
        $this->info('Installing demo components...');

        $demos = [
            'Demo/LandingPage.vue' => $this->getLandingPageDemo(),
            'Demo/Features.vue' => $this->getFeaturesDemo(),
            'Demo/Pricing.vue' => $this->getPricingDemo(),
        ];

        foreach ($demos as $file => $content) {
            $path = resource_path("assets/js/vue/components/{$file}");
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
        
        $routesContent = $this->getVueRoutes();
        $routesPath = base_path('routes/web.php');
        
        if (!File::exists($routesPath) || $this->option('force')) {
            File::put($routesPath, $routesContent);
            $this->line("  ✓ Updated: routes/web.php");
        }
    }

    protected function updateConfig()
    {
        $this->info('Updating configuration...');
        
        $configContent = $this->getVueConfig();
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
                'vue' => '^3.3.0',
                'vue-router' => '^4.2.0',
                'pinia' => '^2.1.0',
                'axios' => '^1.4.0',
                '@vueuse/core' => '^10.4.0',
            ]);
            
            $packageJson['devDependencies'] = array_merge($packageJson['devDependencies'] ?? [], [
                '@vitejs/plugin-vue' => '^4.2.0',
                'vite' => '^4.4.0',
            ]);
            
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

    // Vue Component methods
    protected function getLoginFormComponent()
    {
        return '<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-100">
    <div class="max-w-md w-full space-y-8">
      <div class="text-center">
        <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
          Sign in to your account
        </h2>
      </div>
      
      <form @submit.prevent="login" class="mt-8 space-y-6">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">
            Email Address
          </label>
          <input 
            id="email" 
            v-model="form.email" 
            type="email" 
            required 
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
          >
        </div>
        
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">
            Password
          </label>
          <input 
            id="password" 
            v-model="form.password" 
            type="password" 
            required 
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
          >
        </div>
        
        <div>
          <button 
            type="submit" 
            :disabled="loading"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 disabled:opacity-50"
          >
            <span v-if="loading">Signing in...</span>
            <span v-else>Sign in</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";

const authStore = useAuthStore();
const router = useRouter();

const loading = ref(false);
const form = reactive({
  email: "",
  password: "",
});

const login = async () => {
  loading.value = true;
  try {
    await authStore.login(form);
    router.push("/dashboard");
  } catch (error) {
    console.error("Login failed:", error);
  } finally {
    loading.value = false;
  }
};
</script>';
    }

    protected function getRegisterFormComponent()
    {
        return '<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-100">
    <div class="max-w-md w-full space-y-8">
      <div class="text-center">
        <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
          Create your account
        </h2>
      </div>
      
      <form @submit.prevent="register" class="mt-8 space-y-6">
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700">
            Full Name
          </label>
          <input 
            id="name" 
            v-model="form.name" 
            type="text" 
            required 
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
          >
        </div>
        
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">
            Email Address
          </label>
          <input 
            id="email" 
            v-model="form.email" 
            type="email" 
            required 
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
          >
        </div>
        
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">
            Password
          </label>
          <input 
            id="password" 
            v-model="form.password" 
            type="password" 
            required 
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500"
          >
        </div>
        
        <div>
          <button 
            type="submit" 
            :disabled="loading"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 disabled:opacity-50"
          >
            <span v-if="loading">Creating account...</span>
            <span v-else>Create Account</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from "vue";
import { useAuthStore } from "@/stores/auth";
import { useRouter } from "vue-router";

const authStore = useAuthStore();
const router = useRouter();

const loading = ref(false);
const form = reactive({
  name: "",
  email: "",
  password: "",
});

const register = async () => {
  loading.value = true;
  try {
    await authStore.register(form);
    router.push("/dashboard");
  } catch (error) {
    console.error("Registration failed:", error);
  } finally {
    loading.value = false;
  }
};
</script>';
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

    // View methods
    protected function getLoginView() { return '<!-- Login View -->'; }
    protected function getRegisterView() { return '<!-- Register View -->'; }
    protected function getForgotPasswordView() { return '<!-- Forgot Password View -->'; }
    protected function getResetPasswordView() { return '<!-- Reset Password View -->'; }
    protected function getSocialLoginView() { return '<!-- Social Login View -->'; }
    protected function getProfileView() { return '<!-- Profile View -->'; }
    protected function getAdminDashboardView() { return '<!-- Admin Dashboard View -->'; }
    protected function getAdminUsersView() { return '<!-- Admin Users View -->'; }
    protected function getAdminRolesView() { return '<!-- Admin Roles View -->'; }
    protected function getUserDashboardView() { return '<!-- User Dashboard View -->'; }
    protected function getUserProfileView() { return '<!-- User Profile View -->'; }

    // Store methods
    protected function getAuthStore()
    {
        return 'import { defineStore } from "pinia";
import axios from "axios";

export const useAuthStore = defineStore("auth", {
  state: () => ({
    user: null,
    token: localStorage.getItem("token"),
    loading: false,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
  },

  actions: {
    async login(credentials) {
      this.loading = true;
      try {
        const response = await axios.post("/api/auth/login", credentials);
        this.token = response.data.token;
        this.user = response.data.user;
        localStorage.setItem("token", this.token);
        axios.defaults.headers.common["Authorization"] = `Bearer ${this.token}`;
      } catch (error) {
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async register(userData) {
      this.loading = true;
      try {
        const response = await axios.post("/api/auth/register", userData);
        this.token = response.data.token;
        this.user = response.data.user;
        localStorage.setItem("token", this.token);
        axios.defaults.headers.common["Authorization"] = `Bearer ${this.token}`;
      } catch (error) {
        throw error;
      } finally {
        this.loading = false;
      }
    },

    async logout() {
      this.user = null;
      this.token = null;
      localStorage.removeItem("token");
      delete axios.defaults.headers.common["Authorization"];
    },
  },
});';
    }

    protected function getUserStore() { return '<!-- User Store -->'; }
    protected function getAdminStore() { return '<!-- Admin Store -->'; }
    protected function getSecurityStore() { return '<!-- Security Store -->'; }

    // Composable methods
    protected function getUseAuthComposable()
    {
        return 'import { computed } from "vue";
import { useAuthStore } from "@/stores/auth";

export function useAuth() {
  const authStore = useAuthStore();

  return {
    user: computed(() => authStore.user),
    isAuthenticated: computed(() => authStore.isAuthenticated),
    login: authStore.login,
    register: authStore.register,
    logout: authStore.logout,
    loading: computed(() => authStore.loading),
  };
}';
    }

    protected function getUseUserComposable() { return '<!-- Use User Composable -->'; }
    protected function getUseSecurityComposable() { return '<!-- Use Security Composable -->'; }
    protected function getUseNotificationsComposable() { return '<!-- Use Notifications Composable -->'; }

    // Demo methods
    protected function getLandingPageDemo() { return '<!-- Landing Page Demo -->'; }
    protected function getFeaturesDemo() { return '<!-- Features Demo -->'; }
    protected function getPricingDemo() { return '<!-- Pricing Demo -->'; }

    // Configuration methods
    protected function getVueRoutes()
    {
        return '<?php

use Illuminate\Support\Facades\Route;

// Vue.js SPA Routes
Route::get("/{any}", function () {
    return view("vue.app");
})->where("any", ".*");';
    }

    protected function getVueConfig()
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
    "vue" => [
        "enabled" => true,
        "spa" => true,
        "components" => [
            "auth" => true,
            "profile" => true,
            "admin" => true,
        ],
    ],
];';
    }
}
