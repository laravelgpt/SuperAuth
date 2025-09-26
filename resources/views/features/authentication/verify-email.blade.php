@extends('superauth::shared.layouts.auth')

@section('title', 'Verify Email')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-12 w-12 bg-gradient-to-r from-purple-600 to-blue-600 rounded-full flex items-center justify-center">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
                Verify your email address
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                We've sent a verification link to your email address.
            </p>
        </div>

        <!-- Verification Form -->
        <div class="bg-white dark:bg-gray-800 py-8 px-6 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="text-center space-y-6">
                <!-- Email Icon -->
                <div class="mx-auto w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>

                <!-- Instructions -->
                <div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                        Check your email
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        We've sent a verification link to <strong>{{ $email ?? 'your email address' }}</strong>
                    </p>
                </div>

                <!-- Resend Button -->
                <form action="{{ route('superauth.auth.verify-email.resend') }}" method="POST" class="space-y-4">
                    @csrf
                    <button 
                        type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200 transform hover:scale-105"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Resend Verification Email
                    </button>
                </form>

                <!-- Help Text -->
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <p>Didn't receive the email? Check your spam folder or try again.</p>
                </div>

                <!-- Back to Login -->
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a 
                        href="{{ route('superauth.auth.login') }}" 
                        class="text-sm text-purple-600 dark:text-purple-400 hover:text-purple-500 dark:hover:text-purple-300 font-medium transition duration-200"
                    >
                        ← Back to Login
                    </a>
                </div>
            </div>
        </div>

        <!-- Additional Help -->
        <div class="text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Need help? 
                <a href="{{ route('superauth.contact') }}" class="font-medium text-purple-600 dark:text-purple-400 hover:text-purple-500 dark:hover:text-purple-300 transition duration-200">
                    Contact Support
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
