<div class="space-y-4">
    <!-- Enhanced Breach Check Input -->
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ $label ?? 'Enhanced Password Security Check' }}
        </label>
        <div class="relative">
            <input 
                id="password" 
                type="{{ $showPassword ? 'text' : 'password' }}" 
                wire:model="password" 
                wire:input="checkEnhancedBreach"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('password') border-red-500 @enderror"
                placeholder="{{ $placeholder ?? 'Enter password for comprehensive security check' }}"
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

    <!-- Enhanced Breach Check Results -->
    @if($password && strlen($password) > 0)
        <div class="space-y-3">
            <!-- Loading State -->
            @if($isChecking)
                <div class="flex items-center justify-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-blue-600 dark:text-blue-400">Running comprehensive security analysis...</span>
                </div>
            @else
                <!-- Security Score -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Security Score</h4>
                        <span class="text-sm font-medium {{ $securityScore >= 80 ? 'text-green-600' : ($securityScore >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                            {{ $securityScore }}/100
                        </span>
                    </div>
                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                        <div class="h-2 rounded-full transition-all duration-500 {{ $securityScore >= 80 ? 'bg-green-500' : ($securityScore >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                             style="width: {{ $securityScore }}%"></div>
                    </div>
                </div>

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
                                </p>
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
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Detailed Analysis -->
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Detailed Analysis:</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Breach Count:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $breachCount }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Risk Level:</span>
                                <span class="font-medium {{ $riskLevel === 'High' ? 'text-red-600' : ($riskLevel === 'Medium' ? 'text-yellow-600' : 'text-green-600') }}">
                                    {{ $riskLevel }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">API Response:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $apiResponseTime }}ms</span>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Cache Status:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $cacheStatus }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Last Checked:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $lastChecked }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Confidence:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $confidence }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Recommendations -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">🔒 Enhanced Security Recommendations:</h4>
                    <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                        @foreach($recommendations as $recommendation)
                            <li>• {{ $recommendation }}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- Advanced Security Tips -->
                <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-purple-800 dark:text-purple-200 mb-2">🛡️ Advanced Security Tips:</h4>
                    <ul class="text-sm text-purple-700 dark:text-purple-300 space-y-1">
                        <li>• Use a password manager for unique passwords</li>
                        <li>• Enable two-factor authentication everywhere possible</li>
                        <li>• Regularly update your passwords (every 90 days)</li>
                        <li>• Avoid using personal information in passwords</li>
                        <li>• Consider using passphrases instead of passwords</li>
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <!-- Help Text -->
    <div class="text-xs text-gray-500 dark:text-gray-400">
        <p>Enhanced security check includes breach detection, risk assessment, and personalized recommendations.</p>
    </div>
</div>
