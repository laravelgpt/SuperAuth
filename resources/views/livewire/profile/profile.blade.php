<div class="space-y-6">
    <!-- Header -->
    <div class="glass-morphism rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">Profile Settings</h1>
                <p class="text-gray-200 mt-2">Manage your account information and preferences</p>
            </div>
            <div class="flex items-center space-x-4">
                <button wire:click="saveProfile" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="glass-morphism rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-white/20">
            <h3 class="text-lg font-semibold text-white">Personal Information</h3>
        </div>
        <div class="p-6">
            <form wire:submit.prevent="saveProfile" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200 mb-2">Full Name</label>
                        <input 
                            type="text" 
                            wire:model="profileForm.name" 
                            class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter your full name"
                        >
                        @error('profileForm.name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200 mb-2">Email Address</label>
                        <input 
                            type="email" 
                            wire:model="profileForm.email" 
                            class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter your email"
                        >
                        @error('profileForm.email') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200 mb-2">Phone Number</label>
                        <input 
                            type="tel" 
                            wire:model="profileForm.phone" 
                            class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter your phone number"
                        >
                        @error('profileForm.phone') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div>
                        <label class="block text-sm font-medium text-gray-200 mb-2">Date of Birth</label>
                        <input 
                            type="date" 
                            wire:model="profileForm.date_of_birth" 
                            class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                        @error('profileForm.date_of_birth') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Bio -->
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-2">Bio</label>
                    <textarea 
                        wire:model="profileForm.bio" 
                        rows="4" 
                        class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Tell us about yourself..."
                    ></textarea>
                    @error('profileForm.bio') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Location -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-200 mb-2">City</label>
                        <input 
                            type="text" 
                            wire:model="profileForm.city" 
                            class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter your city"
                        >
                        @error('profileForm.city') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-200 mb-2">Country</label>
                        <select 
                            wire:model="profileForm.country" 
                            class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        >
                            <option value="">Select Country</option>
                            <option value="US">United States</option>
                            <option value="CA">Canada</option>
                            <option value="GB">United Kingdom</option>
                            <option value="AU">Australia</option>
                            <option value="DE">Germany</option>
                            <option value="FR">France</option>
                            <option value="IT">Italy</option>
                            <option value="ES">Spain</option>
                            <option value="NL">Netherlands</option>
                            <option value="SE">Sweden</option>
                            <option value="NO">Norway</option>
                            <option value="DK">Denmark</option>
                            <option value="FI">Finland</option>
                            <option value="CH">Switzerland</option>
                            <option value="AT">Austria</option>
                            <option value="BE">Belgium</option>
                            <option value="IE">Ireland</option>
                            <option value="PT">Portugal</option>
                            <option value="GR">Greece</option>
                            <option value="PL">Poland</option>
                            <option value="CZ">Czech Republic</option>
                            <option value="HU">Hungary</option>
                            <option value="RO">Romania</option>
                            <option value="BG">Bulgaria</option>
                            <option value="HR">Croatia</option>
                            <option value="SI">Slovenia</option>
                            <option value="SK">Slovakia</option>
                            <option value="LT">Lithuania</option>
                            <option value="LV">Latvia</option>
                            <option value="EE">Estonia</option>
                            <option value="LU">Luxembourg</option>
                            <option value="MT">Malta</option>
                            <option value="CY">Cyprus</option>
                        </select>
                        @error('profileForm.country') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Security Settings -->
    <div class="glass-morphism rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-white/20">
            <h3 class="text-lg font-semibold text-white">Security Settings</h3>
        </div>
        <div class="p-6">
            <div class="space-y-6">
                <!-- Current Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-2">Current Password</label>
                    <div class="relative">
                        <input 
                            type="{{ $showCurrentPassword ? 'text' : 'password' }}" 
                            wire:model="currentPassword" 
                            class="w-full px-3 py-2 pr-10 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter your current password"
                        >
                        <button 
                            type="button" 
                            wire:click="toggleCurrentPasswordVisibility" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        >
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                @if($showCurrentPassword)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                @endif
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- New Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-2">New Password</label>
                    <div class="relative">
                        <input 
                            type="{{ $showNewPassword ? 'text' : 'password' }}" 
                            wire:model="newPassword" 
                            wire:input="updatedNewPassword"
                            class="w-full px-3 py-2 pr-10 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Enter your new password"
                        >
                        <button 
                            type="button" 
                            wire:click="toggleNewPasswordVisibility" 
                            class="absolute inset-y-0 right-0 pr-3 flex items-center"
                        >
                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                @if($showNewPassword)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                @endif
                            </svg>
                        </button>
                    </div>
                    @if($newPassword && strlen($newPassword) > 0)
                        <div class="mt-2">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-sm text-gray-300">Password Strength</span>
                                <span class="text-sm font-medium {{ $passwordStrength >= 80 ? 'text-green-400' : ($passwordStrength >= 60 ? 'text-yellow-400' : 'text-red-400') }}">
                                    {{ $passwordStrength }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all duration-500 {{ $passwordStrength >= 80 ? 'bg-green-500' : ($passwordStrength >= 60 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                     style="width: {{ $passwordStrength }}%"></div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Confirm New Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-200 mb-2">Confirm New Password</label>
                    <input 
                        type="password" 
                        wire:model="newPasswordConfirmation" 
                        class="w-full px-3 py-2 bg-white/10 border border-white/20 rounded-lg text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Confirm your new password"
                    >
                </div>

                <!-- Change Password Button -->
                <div>
                    <button 
                        wire:click="changePassword" 
                        class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors"
                    >
                        Change Password
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Social Accounts -->
    <div class="glass-morphism rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-white/20">
            <h3 class="text-lg font-semibold text-white">Connected Accounts</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                @forelse($socialAccounts as $account)
                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <!-- Social provider icon based on provider -->
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-white">{{ ucfirst($account->provider) }}</h4>
                                <p class="text-xs text-gray-300">{{ $account->email }}</p>
                            </div>
                        </div>
                        <button 
                            wire:click="disconnectAccount({{ $account->id }})" 
                            wire:confirm="Are you sure you want to disconnect this account?"
                            class="text-red-400 hover:text-red-300 transition-colors"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-200">No connected accounts</h3>
                        <p class="mt-1 text-sm text-gray-300">Connect your social accounts for easier login</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Account Actions -->
    <div class="glass-morphism rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-white/20">
            <h3 class="text-lg font-semibold text-white">Account Actions</h3>
        </div>
        <div class="p-6">
            <div class="space-y-4">
                <button 
                    wire:click="exportData" 
                    class="w-full flex items-center justify-center px-4 py-2 border border-blue-500 text-blue-400 rounded-lg hover:bg-blue-500/10 transition-colors"
                >
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    Export My Data
                </button>

                <button 
                    wire:click="deleteAccount" 
                    wire:confirm="Are you sure you want to delete your account? This action cannot be undone."
                    class="w-full flex items-center justify-center px-4 py-2 border border-red-500 text-red-400 rounded-lg hover:bg-red-500/10 transition-colors"
                >
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd"></path>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Delete Account
                </button>
            </div>
        </div>
    </div>
</div>
