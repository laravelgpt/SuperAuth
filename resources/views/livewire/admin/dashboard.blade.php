<div class="space-y-6">
    <!-- Header -->
    <div class="glass-morphism rounded-lg p-6">
        <h1 class="text-3xl font-bold text-white mb-2">Admin Dashboard</h1>
        <p class="text-gray-200">Welcome to SuperAuth Admin Panel</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="glass-morphism rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-200">Total Users</p>
                    <p class="text-2xl font-semibold text-white">{{ $stats['total_users'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Active Users -->
        <div class="glass-morphism rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-200">Active Users</p>
                    <p class="text-2xl font-semibold text-white">{{ $stats['active_users'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Admin Users -->
        <div class="glass-morphism rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-200">Admin Users</p>
                    <p class="text-2xl font-semibold text-white">{{ $stats['admin_users'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <!-- Recent Logins -->
        <div class="glass-morphism rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-200">Recent Logins</p>
                    <p class="text-2xl font-semibold text-white">{{ $stats['recent_logins'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- AI Insights -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Security Alerts -->
        <div class="glass-morphism rounded-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Security Alerts</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-red-500/20 rounded-lg">
                    <span class="text-red-200">High Risk Users</span>
                    <span class="text-red-100 font-semibold">3</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-yellow-500/20 rounded-lg">
                    <span class="text-yellow-200">Suspicious Logins</span>
                    <span class="text-yellow-100 font-semibold">7</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-blue-500/20 rounded-lg">
                    <span class="text-blue-200">Failed Attempts</span>
                    <span class="text-blue-100 font-semibold">12</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="glass-morphism rounded-lg p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Recent Activity</h3>
            <div class="space-y-3">
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                    <span class="text-gray-200 text-sm">User John Doe logged in</span>
                    <span class="text-gray-400 text-xs">2 minutes ago</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                    <span class="text-gray-200 text-sm">New user registered</span>
                    <span class="text-gray-400 text-xs">5 minutes ago</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                    <span class="text-gray-200 text-sm">Password reset requested</span>
                    <span class="text-gray-400 text-xs">10 minutes ago</span>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                    <span class="text-gray-200 text-sm">Failed login attempt</span>
                    <span class="text-gray-400 text-xs">15 minutes ago</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="glass-morphism rounded-lg p-6">
        <h3 class="text-lg font-semibold text-white mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="{{ route('admin.users') }}" class="flex items-center p-4 bg-blue-500/20 rounded-lg hover:bg-blue-500/30 transition-colors">
                <svg class="w-6 h-6 text-blue-300 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                </svg>
                <span class="text-blue-200">Manage Users</span>
            </a>
            
            <a href="{{ route('admin.roles') }}" class="flex items-center p-4 bg-green-500/20 rounded-lg hover:bg-green-500/30 transition-colors">
                <svg class="w-6 h-6 text-green-300 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-green-200">Manage Roles</span>
            </a>
            
            <a href="{{ route('admin.ai-dashboard') }}" class="flex items-center p-4 bg-purple-500/20 rounded-lg hover:bg-purple-500/30 transition-colors">
                <svg class="w-6 h-6 text-purple-300 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-purple-200">AI Dashboard</span>
            </a>
        </div>
    </div>
</div>
