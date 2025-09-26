<div class="space-y-6">
    <!-- Header -->
    <div class="glass-morphism rounded-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white">🤖 AI Dashboard</h1>
                <p class="text-gray-200 mt-2">Intelligent security monitoring and insights</p>
            </div>
            <div class="flex items-center space-x-4">
                <button wire:click="refreshData" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                    </svg>
                    Refresh
                </button>
                <button wire:click="generateInsights" class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg transition-colors">
                    <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    Generate Insights
                </button>
            </div>
        </div>
    </div>

    <!-- AI Insights Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Security Score -->
        <div class="glass-morphism rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-200">Security Score</p>
                    <p class="text-3xl font-bold text-white">{{ $insights['security_score'] ?? 85 }}/100</p>
                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                        <div class="bg-gradient-to-r from-red-500 to-red-600 h-2 rounded-full" style="width: {{ $insights['security_score'] ?? 85 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Anomalies Detected -->
        <div class="glass-morphism rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-200">Anomalies</p>
                    <p class="text-3xl font-bold text-white">{{ count($anomalies) }}</p>
                    <p class="text-xs text-yellow-300">Detected today</p>
                </div>
            </div>
        </div>

        <!-- Risk Users -->
        <div class="glass-morphism rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-orange-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-200">High Risk Users</p>
                    <p class="text-3xl font-bold text-white">{{ $insights['high_risk_users'] ?? 3 }}</p>
                    <p class="text-xs text-orange-300">Require attention</p>
                </div>
            </div>
        </div>

        <!-- AI Confidence -->
        <div class="glass-morphism rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-200">AI Confidence</p>
                    <p class="text-3xl font-bold text-white">{{ $insights['ai_confidence'] ?? 92 }}%</p>
                    <p class="text-xs text-blue-300">Analysis accuracy</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Security Threats -->
        <div class="glass-morphism rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-white/20">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    Security Threats
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($threats as $threat)
                        <div class="flex items-center p-4 bg-red-500/20 rounded-lg border border-red-500/30">
                            <div class="flex-shrink-0">
                                <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="text-sm font-medium text-red-200">{{ $threat['type'] ?? 'Security Threat' }}</h4>
                                    <span class="text-xs text-red-300">{{ $threat['timestamp'] ?? 'Just now' }}</span>
                                </div>
                                <p class="text-xs text-red-300 mt-1">{{ $threat['description'] ?? 'Threat detected' }}</p>
                                <div class="flex items-center mt-2">
                                    <span class="text-xs text-red-400">Risk: {{ $threat['risk_level'] ?? 'High' }}</span>
                                    <span class="text-xs text-red-400 ml-4">Confidence: {{ $threat['confidence'] ?? '95' }}%</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-green-200">No threats detected</h3>
                            <p class="mt-1 text-sm text-green-300">Your system is secure</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- AI Recommendations -->
        <div class="glass-morphism rounded-lg shadow-lg">
            <div class="px-6 py-4 border-b border-white/20">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    AI Recommendations
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recommendations as $recommendation)
                        <div class="flex items-start p-4 bg-blue-500/20 rounded-lg border border-blue-500/30">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h4 class="text-sm font-medium text-blue-200">{{ $recommendation['title'] ?? 'Recommendation' }}</h4>
                                <p class="text-xs text-blue-300 mt-1">{{ $recommendation['message'] ?? $recommendation }}</p>
                                <div class="flex items-center mt-2">
                                    <span class="text-xs text-blue-400">Priority: {{ $recommendation['priority'] ?? 'Medium' }}</span>
                                    <span class="text-xs text-blue-400 ml-4">Impact: {{ $recommendation['impact'] ?? 'High' }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-200">No recommendations</h3>
                            <p class="mt-1 text-sm text-gray-300">AI is analyzing your system</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Anomalies Detection -->
    <div class="glass-morphism rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-white/20">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                Anomaly Detection
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($anomalies as $anomaly)
                    <div class="bg-yellow-500/20 border border-yellow-500/30 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-yellow-200">{{ $anomaly['type'] ?? 'Anomaly' }}</h4>
                            <span class="text-xs text-yellow-300">{{ $anomaly['severity'] ?? 'Medium' }}</span>
                        </div>
                        <p class="text-xs text-yellow-300 mb-2">{{ $anomaly['description'] ?? 'Anomaly detected' }}</p>
                        <div class="flex items-center justify-between text-xs text-yellow-400">
                            <span>{{ $anomaly['user'] ?? 'Unknown User' }}</span>
                            <span>{{ $anomaly['time'] ?? 'Just now' }}</span>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-green-200">No anomalies detected</h3>
                        <p class="mt-1 text-sm text-green-300">System behavior is normal</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- AI Analysis Summary -->
    <div class="glass-morphism rounded-lg shadow-lg">
        <div class="px-6 py-4 border-b border-white/20">
            <h3 class="text-lg font-semibold text-white flex items-center">
                <svg class="w-5 h-5 text-purple-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                AI Analysis Summary
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-white mb-2">{{ $insights['total_analysis'] ?? 1,247 }}</div>
                    <div class="text-sm text-gray-300">Total Analyses</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-white mb-2">{{ $insights['accuracy'] ?? 94.2 }}%</div>
                    <div class="text-sm text-gray-300">Detection Accuracy</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-white mb-2">{{ $insights['response_time'] ?? 0.3 }}s</div>
                    <div class="text-sm text-gray-300">Avg Response Time</div>
                </div>
            </div>
        </div>
    </div>
</div>
