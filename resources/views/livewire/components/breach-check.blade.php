<div class="space-y-4">
    <!-- Breach Check Input -->
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ $label ?? 'Check Password Security' }}
        </label>
        <div class="relative">
            <input 
                id="password" 
                type="{{ $showPassword ? 'text' : 'password' }}" 
                wire:model="password" 
                wire:input="checkBreach"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('password') border-red-500 @enderror"
                placeholder="{{ $placeholder ?? 'Enter password to check' }}"
                {{ $required ? 'required' : '' }}
            >
            <button 
                type="button" 
                wire:click="togglePasswordVisibility" 
                class="absolute inset-y-0 right-0 pr-3 flex items-center"
            >
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    @if($showPassword)
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    @endif
                </svg>
            </button>
        </div>
        @error('password')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Breach Check Results -->
    @if($password && strlen($password) > 0)
        <div class="space-y-3">
            <!-- Loading State -->
            @if($isChecking)
                <div class="flex items-center justify-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-blue-600 dark:text-blue-400">Checking password security...</span>
                </div>
            @else
                <!-- Breach Check Results -->
                @if($breachCount > 0)
                    <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                                    ⚠️ Password Found in Data Breaches
                                </h3>
                                <p class="mt-1 text-sm text-red-700 dark:text-red-300">
                                    This password has been found in <strong>{{ $breachCount }}</strong> data breaches. 
                                    Please choose a different, more secure password.
                                </p>
                                <div class="mt-2 text-xs text-red-600 dark:text-red-400">
                                    <p>Last checked: {{ $lastChecked ?? 'Just now' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                        <div class="flex">
                            <svg class="w-5 h-5 text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-green-800 dark:text-green-200">
                                    ✅ Password is Secure
                                </h3>
                                <p class="mt-1 text-sm text-green-700 dark:text-green-300">
                                    This password has not been found in any known data breaches.
                                </p>
                                <div class="mt-2 text-xs text-green-600 dark:text-green-400">
                                    <p>Last checked: {{ $lastChecked ?? 'Just now' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Additional Security Information -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Security Information:</h4>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Breach Count:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $breachCount }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Status:</span>
                            <span class="font-medium {{ $breachCount > 0 ? 'text-red-600 dark:text-red-400' : 'text-green-600 dark:text-green-400' }}">
                                {{ $breachCount > 0 ? 'Compromised' : 'Secure' }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">API Response:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $apiResponseTime ?? 'N/A' }}ms</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Cache Status:</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ $cacheStatus ?? 'Fresh' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Security Recommendations -->
                @if($breachCount > 0)
                    <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-yellow-800 dark:text-yellow-200 mb-2">🔒 Security Recommendations:</h4>
                        <ul class="text-sm text-yellow-700 dark:text-yellow-300 space-y-1">
                            <li>• Use a unique password for each account</li>
                            <li>• Include a mix of uppercase, lowercase, numbers, and symbols</li>
                            <li>• Make it at least 12 characters long</li>
                            <li>• Consider using a password manager</li>
                            <li>• Enable two-factor authentication where possible</li>
                        </ul>
                    </div>
                @endif
            @endif
        </div>
    @endif

    <!-- Help Text -->
    <div class="text-xs text-gray-500 dark:text-gray-400">
        <p>This check uses the HaveIBeenPwned API to verify if your password has been found in any known data breaches.</p>
    </div>
</div>
