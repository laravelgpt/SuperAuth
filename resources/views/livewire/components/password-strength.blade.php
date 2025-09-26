<div class="space-y-4">
    <!-- Password Input -->
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            {{ $label ?? 'Password' }}
        </label>
        <div class="relative">
            <input 
                id="password" 
                type="{{ $showPassword ? 'text' : 'password' }}" 
                wire:model="password" 
                wire:input="updatedPassword"
                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white @error('password') border-red-500 @enderror"
                placeholder="{{ $placeholder ?? 'Enter your password' }}"
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

    <!-- Password Strength Indicator -->
    @if($password && strlen($password) > 0)
        <div class="space-y-3">
            <!-- Strength Bar -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Password Strength</span>
                    <span class="text-sm font-medium {{ $strength >= 80 ? 'text-green-600' : ($strength >= 60 ? 'text-yellow-600' : ($strength >= 40 ? 'text-orange-600' : 'text-red-600')) }}">
                        {{ $strength }}%
                    </span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    <div class="h-2 rounded-full transition-all duration-500 {{ $strength >= 80 ? 'bg-green-500' : ($strength >= 60 ? 'bg-yellow-500' : ($strength >= 40 ? 'bg-orange-500' : 'bg-red-500')) }}" 
                         style="width: {{ $strength }}%"></div>
                </div>
            </div>

            <!-- Strength Level -->
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600 dark:text-gray-400">Level:</span>
                <span class="px-2 py-1 text-xs font-medium rounded-full {{ $strength >= 80 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : ($strength >= 60 ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : ($strength >= 40 ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200')) }}">
                    {{ $strength >= 80 ? 'Very Strong' : ($strength >= 60 ? 'Strong' : ($strength >= 40 ? 'Medium' : 'Weak')) }}
                </span>
            </div>

            <!-- Requirements Checklist -->
            <div class="space-y-2">
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Requirements:</h4>
                <div class="space-y-1">
                    @foreach($requirements as $requirement => $met)
                        <div class="flex items-center text-sm">
                            <svg class="w-4 h-4 mr-3 {{ $met ? 'text-green-500' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                @if($met)
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                @else
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                @endif
                            </svg>
                            <span class="{{ $met ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400' }}">
                                {{ ucfirst(str_replace('_', ' ', $requirement)) }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Breach Check Alert -->
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
                        </div>
                    </div>
                </div>
            @elseif($password && strlen($password) > 0)
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

            <!-- Recommendations -->
            @if($recommendations && count($recommendations) > 0)
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-blue-800 dark:text-blue-200 mb-2">💡 Recommendations:</h4>
                    <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                        @foreach($recommendations as $recommendation)
                            <li>• {{ $recommendation }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <!-- Character Analysis -->
    @if($password && strlen($password) > 0)
        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Character Analysis:</h4>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Length:</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ strlen($password) }} characters</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Unique chars:</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $uniqueChars ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Uppercase:</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $uppercaseCount ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Lowercase:</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $lowercaseCount ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Numbers:</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $numberCount ?? 0 }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600 dark:text-gray-400">Symbols:</span>
                    <span class="font-medium text-gray-900 dark:text-white">{{ $symbolCount ?? 0 }}</span>
                </div>
            </div>
        </div>
    @endif
</div>
