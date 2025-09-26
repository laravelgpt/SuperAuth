<div class="space-y-6">
    <!-- Header -->
    <div class="glass-morphism rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">User Role Assignment</h1>
                <p class="text-gray-200 mt-2">Manage roles for {{ $user->name ?? 'Selected User' }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.users') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                    </svg>
                    Back to Users
                </a>
                <button wire:click="assignRole" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    Assign Role
                </button>
            </div>
        </div>
    </div>

    <!-- User Information -->
    <div class="glass-morphism rounded-lg p-6">
        <div class="flex items-center space-x-6">
            <div class="flex-shrink-0">
                <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                    <span class="text-white text-xl font-bold">{{ substr($user->name ?? 'U', 0, 1) }}</span>
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-semibold text-white">{{ $user->name ?? 'Unknown User' }}</h3>
                <p class="text-gray-300">{{ $user->email ?? 'No email' }}</p>
                <div class="flex items-center mt-2 space-x-4">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <span class="text-sm text-gray-400">Member since {{ $user->created_at?->format('M Y') ?? 'Unknown' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Roles -->
    <div class="glass-morphism rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-white/20">
            <h3 class="text-lg font-semibold text-white">Current Roles</h3>
        </div>
        <div class="p-6">
            @if($userRoles->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($userRoles as $role)
                        <div class="bg-blue-500/20 border border-blue-500/30 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-medium text-blue-200">{{ $role->name }}</h4>
                                <button 
                                    wire:click="removeRole({{ $role->id }})" 
                                    wire:confirm="Are you sure you want to remove this role?"
                                    class="text-red-400 hover:text-red-300 transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                            </div>
                            <p class="text-xs text-blue-300 mb-2">{{ $role->description ?? 'No description' }}</p>
                            <div class="flex items-center justify-between text-xs text-blue-400">
                                <span>{{ $role->permissions_count ?? 0 }} permissions</span>
                                @if($role->expires_at)
                                    <span>Expires: {{ $role->expires_at->format('M d, Y') }}</span>
                                @else
                                    <span>Permanent</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-200">No roles assigned</h3>
                    <p class="mt-1 text-sm text-gray-300">This user doesn't have any roles yet</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Available Roles -->
    <div class="glass-morphism rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-white/20">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-white">Available Roles</h3>
                <div class="flex items-center space-x-4">
                    <input 
                        type="text" 
                        wire:model="search" 
                        placeholder="Search roles..." 
                        class="bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                    <select 
                        wire:model="filter" 
                        class="bg-white/10 border border-white/20 rounded-lg px-3 py-2 text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <option value="">All Roles</option>
                        <option value="available">Available</option>
                        <option value="assigned">Already Assigned</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($availableRoles as $role)
                    <div class="bg-gray-500/20 border border-gray-500/30 rounded-lg p-4 hover:bg-gray-500/30 transition-colors">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-200">{{ $role->name }}</h4>
                            <button 
                                wire:click="assignRoleToUser({{ $role->id }})" 
                                class="text-green-400 hover:text-green-300 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <p class="text-xs text-gray-300 mb-2">{{ $role->description ?? 'No description' }}</p>
                        <div class="flex items-center justify-between text-xs text-gray-400">
                            <span>{{ $role->permissions_count ?? 0 }} permissions</span>
                            <span>{{ $role->users_count ?? 0 }} users</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-200">No roles available</h3>
                        <p class="mt-1 text-sm text-gray-300">All roles have been assigned to this user</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Role Assignment Modal -->
    @if($showAssignModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Assign Role</h3>
                </div>
                
                <form wire:submit.prevent="saveRoleAssignment">
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Select Role
                            </label>
                            <select 
                                wire:model="assignmentForm.role_id" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            >
                                <option value="">Choose a role...</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @error('assignmentForm.role_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Expiration Date (Optional)
                            </label>
                            <input 
                                type="date" 
                                wire:model="assignmentForm.expires_at" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                            >
                            <p class="text-xs text-gray-500 mt-1">Leave empty for permanent assignment</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Assigned By
                            </label>
                            <input 
                                type="text" 
                                wire:model="assignmentForm.assigned_by" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                placeholder="Enter assigner name"
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Notes (Optional)
                            </label>
                            <textarea 
                                wire:model="assignmentForm.notes" 
                                rows="3" 
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                placeholder="Add any notes about this assignment"
                            ></textarea>
                        </div>
                    </div>
                    
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-3">
                        <button 
                            type="button" 
                            wire:click="closeAssignModal" 
                            class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-600 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-500 transition-colors"
                        >
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                        >
                            Assign Role
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
