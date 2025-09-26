<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 dark:from-gray-900 dark:to-gray-800 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
                Create your account
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Join us today! Create your account to get started
            </p>
        </div>

        <!-- Registration Form -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-8">
            <form wire:submit.prevent="register" class="space-y-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Full Name
                    </label>
                    <div class="mt-1">
                        <input 
                            id="name" 
                            type="text" 
                            wire:model="name" 
                            autocomplete="name" 
                            required 
                            class="appearance-none relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-gray-700 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('name') border-red-500 @enderror"
                            placeholder="Enter your full name"
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Email address
                    </label>
                    <div class="mt-1">
                        <input 
                            id="email" 
                            type="email" 
                            wire:model="email" 
                            autocomplete="email" 
                            required 
                            class="appearance-none relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-gray-700 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('email') border-red-500 @enderror"
                            placeholder="Enter your email"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Password
                    </label>
                    <div class="mt-1 relative">
                        <input 
                            id="password" 
                            :type="showPassword ? 'text' : 'password'" 
                            wire:model="password" 
                            autocomplete="new-password" 
                            required 
                            class="appearance-none relative block w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-gray-700 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('password') border-red-500 @enderror"
                            placeholder="Enter your password"
                        >
                        <button 
                            type="button" 
                            wire:click="togglePasswordVisibility" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        >
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Confirm Password
                    </label>
                    <div class="mt-1">
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            wire:model="password_confirmation" 
                            autocomplete="new-password" 
                            required 
                            class="appearance-none relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white bg-white dark:bg-gray-700 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('password_confirmation') border-red-500 @enderror"
                            placeholder="Confirm your password"
                        >
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Password Strength Indicator -->
                @if($password && strlen($password) > 0)
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Password Strength</span>
                            <span class="text-sm font-medium {{ $passwordStrength >= 80 ? 'text-green-600' : ($passwordStrength >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $passwordStrength }}%
                            </span>
                        </div>
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="h-2 rounded-full transition-all duration-300 {{ $passwordStrength >= 80 ? 'bg-green-500' : ($passwordStrength >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                 style="width: {{ $passwordStrength }}%"></div>
                        </div>
                        
                        <!-- Password Requirements -->
                        <div class="space-y-1">
                            @foreach($strengthRequirements as $requirement => $met)
                                <div class="flex items-center text-xs">
                                    <svg class="w-4 h-4 mr-2 {{ $met ? 'text-green-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="{{ $met ? 'text-green-600' : 'text-gray-500' }}">
                                        {{ ucfirst(str_replace('_', ' ', $requirement)) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Breach Check Alert -->
                        @if($breachCount > 0)
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-md p-3">
                                <div class="flex">
                                    <svg class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                            Password Found in Data Breaches
                                        </h3>
                                        <p class="mt-1 text-sm text-red-700 dark:text-red-300">
                                            This password has been found in {{ $breachCount }} data breaches. Please choose a different password.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Terms and Conditions -->
                <div class="flex items-center">
                    <input 
                        id="terms" 
                        type="checkbox" 
                        required 
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    >
                    <label for="terms" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                        I agree to the 
                        <a href="#" class="text-blue-600 hover:text-blue-500 dark:text-blue-400">Terms of Service</a> 
                        and 
                        <a href="#" class="text-blue-600 hover:text-blue-500 dark:text-blue-400">Privacy Policy</a>
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove wire:target="register">Create Account</span>
                        <span wire:loading wire:target="register" class="flex items-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Creating account...
                        </span>
                    </button>
                </div>

                <!-- Social Registration -->
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white dark:bg-gray-800 text-gray-500">Or register with</span>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <a href="{{ route('superauth.social.redirect', 'google') }}" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <svg class="w-5 h-5" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                            </svg>
                            <span class="ml-2">Google</span>
                        </a>

                        <a href="{{ route('superauth.social.redirect', 'github') }}" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                            <span class="ml-2">GitHub</span>
                        </a>
                    </div>
                </div>

                <!-- Login Link -->
                <div class="text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Already have an account? 
                        <a href="{{ route('superauth.login') }}" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                            Sign in here
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
