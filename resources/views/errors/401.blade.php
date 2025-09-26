<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>401 - Unauthorized | SuperAuth</title>
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
                <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-yellow-100 mb-6">
                    <svg class="h-12 w-12 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>

                <!-- Error Code -->
                <h1 class="text-6xl font-bold text-white mb-4">401</h1>
                
                <!-- Error Title -->
                <h2 class="text-2xl font-semibold text-white mb-4">Unauthorized Access</h2>
                
                <!-- Error Description -->
                <p class="text-gray-200 mb-8">
                    You need to be authenticated to access this resource. Please log in to continue.
                </p>

                <!-- Action Buttons -->
                <div class="space-y-4">
                    <a href="{{ route('superauth.login') }}" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"></path>
                        </svg>
                        Sign In
                    </a>
                    
                    <a href="{{ route('superauth.register') }}" class="w-full flex justify-center py-3 px-4 border border-white/20 rounded-md text-sm font-medium text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h1a1 1 0 100 2h-1v1a1 1 0 10-2 0v-1H7a1 1 0 100-2h1V7a1 1 0 10-2 0v1H5a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                        </svg>
                        Create Account
                    </a>
                </div>

                <!-- Help Text -->
                <div class="mt-8 text-sm text-gray-300">
                    <p>Don't have an account? <a href="{{ route('superauth.register') }}" class="text-blue-300 hover:text-blue-200">Sign up here</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
