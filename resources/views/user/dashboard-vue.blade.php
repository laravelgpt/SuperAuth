<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>SuperAuth User Dashboard</title>
    
    <!-- Vue.js CDN -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div id="app" class="min-h-screen">
        <!-- Vue.js User Dashboard -->
        <user-dashboard 
            :user="{{ json_encode($user) }}"
            :stats="{{ json_encode($stats) }}"
            :recent-activity="{{ json_encode($recentActivity) }}"
            :security-alerts="{{ json_encode($securityAlerts) }}"
            :recommendations="{{ json_encode($recommendations) }}"
        ></user-dashboard>
    </div>

    <script>
        const { createApp } = Vue;
        
        const UserDashboard = {
            props: ['user', 'stats', 'recentActivity', 'securityAlerts', 'recommendations'],
            template: `
                <div class="min-h-screen gradient-bg">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                        <!-- Header -->
                        <div class="mb-8">
                            <h1 class="text-3xl font-bold text-white">
                                Welcome back, {{ user.name }}!
                            </h1>
                            <p class="text-gray-200 mt-2">
                                Here's what's happening with your account
                            </p>
                        </div>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                            <!-- Total Logins -->
                            <div class="bg-white/10 backdrop-blur-lg rounded-lg shadow-lg p-6 border border-white/20">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-200">Total Logins</p>
                                        <p class="text-2xl font-semibold text-white">{{ stats.total_logins || 0 }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Last Login -->
                            <div class="bg-white/10 backdrop-blur-lg rounded-lg shadow-lg p-6 border border-white/20">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-200">Last Login</p>
                                        <p class="text-sm font-semibold text-white">{{ stats.last_login || 'Never' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Age -->
                            <div class="bg-white/10 backdrop-blur-lg rounded-lg shadow-lg p-6 border border-white/20">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-200">Account Age</p>
                                        <p class="text-sm font-semibold text-white">{{ stats.account_age || 'Unknown' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Security Score -->
                            <div class="bg-white/10 backdrop-blur-lg rounded-lg shadow-lg p-6 border border-white/20">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium text-gray-200">Security Score</p>
                                        <p class="text-sm font-semibold text-white">{{ stats.security_score || 0 }}/100</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Main Content Grid -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Recent Activity -->
                            <div class="lg:col-span-2">
                                <div class="bg-white/10 backdrop-blur-lg rounded-lg shadow-lg border border-white/20">
                                    <div class="px-6 py-4 border-b border-white/20">
                                        <h3 class="text-lg font-semibold text-white">Recent Activity</h3>
                                    </div>
                                    <div class="p-6">
                                        <div class="space-y-4">
                                            <div v-for="activity in recentActivity" :key="activity.id" class="flex items-center space-x-4">
                                                <div class="flex-shrink-0">
                                                    <div class="w-2 h-2 rounded-full" :class="activity.type === 'success' ? 'bg-green-500' : 'bg-red-500'"></div>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm text-white">{{ activity.message }}</p>
                                                    <p class="text-xs text-gray-200">
                                                        {{ activity.time }} • {{ activity.ip }} • {{ activity.device }}
                                                    </p>
                                                </div>
                                            </div>
                                            <div v-if="recentActivity.length === 0" class="text-gray-200 text-center py-4">
                                                No recent activity
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Security & Recommendations -->
                            <div class="space-y-6">
                                <!-- Security Alerts -->
                                <div class="bg-white/10 backdrop-blur-lg rounded-lg shadow-lg border border-white/20">
                                    <div class="px-6 py-4 border-b border-white/20">
                                        <h3 class="text-lg font-semibold text-white">Security Alerts</h3>
                                    </div>
                                    <div class="p-6">
                                        <div v-if="securityAlerts.length > 0" class="space-y-3">
                                            <div v-for="alert in securityAlerts" :key="alert" class="flex items-center p-3 bg-red-500/20 rounded-lg">
                                                <svg class="w-5 h-5 text-red-300 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-sm text-red-200">{{ alert }}</span>
                                            </div>
                                        </div>
                                        <div v-else class="flex items-center p-3 bg-green-500/20 rounded-lg">
                                            <svg class="w-5 h-5 text-green-300 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-sm text-green-200">No security alerts</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Recommendations -->
                                <div class="bg-white/10 backdrop-blur-lg rounded-lg shadow-lg border border-white/20">
                                    <div class="px-6 py-4 border-b border-white/20">
                                        <h3 class="text-lg font-semibold text-white">Recommendations</h3>
                                    </div>
                                    <div class="p-6">
                                        <div v-if="recommendations.length > 0" class="space-y-3">
                                            <div v-for="recommendation in recommendations" :key="recommendation" class="flex items-start p-3 bg-blue-500/20 rounded-lg">
                                                <svg class="w-5 h-5 text-blue-300 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                                </svg>
                                                <div>
                                                    <p class="text-sm font-medium text-blue-100">{{ recommendation.title || 'Recommendation' }}</p>
                                                    <p class="text-xs text-blue-200">{{ recommendation.message || recommendation }}</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div v-else class="text-gray-200 text-center py-4">
                                            No recommendations at this time
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="mt-8">
                            <div class="bg-white/10 backdrop-blur-lg rounded-lg shadow-lg border border-white/20">
                                <div class="px-6 py-4 border-b border-white/20">
                                    <h3 class="text-lg font-semibold text-white">Quick Actions</h3>
                                </div>
                                <div class="p-6">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <a href="/profile" class="flex items-center p-4 bg-blue-500/20 rounded-lg hover:bg-blue-500/30 transition-colors">
                                            <svg class="w-6 h-6 text-blue-300 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-blue-200">Update Profile</span>
                                        </a>
                                        
                                        <a href="#" class="flex items-center p-4 bg-green-500/20 rounded-lg hover:bg-green-500/30 transition-colors">
                                            <svg class="w-6 h-6 text-green-300 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-green-200">Change Password</span>
                                        </a>
                                        
                                        <button @click="refreshData" class="flex items-center p-4 bg-purple-500/20 rounded-lg hover:bg-purple-500/30 transition-colors">
                                            <svg class="w-6 h-6 text-purple-300 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="text-purple-200">Refresh Data</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `,
            methods: {
                refreshData() {
                    // Refresh data logic
                    console.log('Refreshing data...');
                }
            }
        };

        createApp({
            components: {
                UserDashboard
            }
        }).mount('#app');
    </script>
</body>
</html>
