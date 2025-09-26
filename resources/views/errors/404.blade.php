<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page Not Found | SuperAuth</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-morphism {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="h-full gradient-bg">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="glass-morphism rounded-lg shadow-xl p-8 text-center">
                <!-- Error Icon -->
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-blue-100 mb-6">
                    <svg class="h-12 w-12 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.29-1.009-5.824-2.57M15 6.291A7.962 7.962 0 0012 5c-2.34 0-4.29 1.009-5.824 2.57" />
                    </svg>
                </div>

                <!-- Error Code -->
                <h1 class="text-6xl font-bold text-white mb-4">404</h1>
                
                <!-- Error Title -->
                <h2 class="text-2xl font-semibold text-white mb-4">Page Not Found</h2>
                
                <!-- Error Description -->
                <p class="text-gray-200 mb-8">
                    The page you're looking for doesn't exist or has been moved.
                </p>

                <!-- Action Buttons -->
                <div class="space-y-4">
                    <a href="{{ url()->previous() }}" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                        </svg>
                        Go Back
                    </a>
                    
                    <a href="{{ route('superauth.dashboard') }}" class="w-full flex justify-center py-3 px-4 border border-white/20 rounded-md text-sm font-medium text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                        Go to Dashboard
                    </a>
                </div>

                <!-- Help Text -->
                <div class="mt-8 text-sm text-gray-300">
                    <p>Looking for something specific? <a href="{{ route('superauth.help') }}" class="text-blue-300 hover:text-blue-200">Browse our help center</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
