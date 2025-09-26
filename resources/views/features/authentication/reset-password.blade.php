@extends('superauth::shared.layouts.auth')

@section('title', 'Reset Password')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-12 w-12 bg-gradient-to-r from-purple-600 to-blue-600 rounded-full flex items-center justify-center">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
                Reset your password
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Enter your new password below to complete the reset process.
            </p>
        </div>

        <!-- Reset Password Form -->
        <div class="bg-white dark:bg-gray-800 py-8 px-6 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700">
            <form class="space-y-6" action="{{ route('superauth.auth.reset-password') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">
                
                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        New Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="new-password" 
                            required 
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-200 @error('password') border-red-500 @enderror"
                            placeholder="Enter your new password"
                        >
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation Input -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Confirm New Password
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            autocomplete="new-password" 
                            required 
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-200 @error('password_confirmation') border-red-500 @enderror"
                            placeholder="Confirm your new password"
                        >
                    </div>
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Strength Indicator -->
                <div class="password-strength-indicator">
                    <div class="flex items-center space-x-2 mb-2">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Password Strength:</span>
                        <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                            <div class="password-strength-bar bg-red-500 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <span class="password-strength-text text-sm font-medium text-red-600 dark:text-red-400">Weak</span>
                    </div>
                    <div class="password-requirements text-xs text-gray-600 dark:text-gray-400 space-y-1">
                        <div class="requirement" data-requirement="length">
                            <span class="requirement-icon text-red-500">✗</span>
                            At least 8 characters
                        </div>
                        <div class="requirement" data-requirement="uppercase">
                            <span class="requirement-icon text-red-500">✗</span>
                            One uppercase letter
                        </div>
                        <div class="requirement" data-requirement="lowercase">
                            <span class="requirement-icon text-red-500">✗</span>
                            One lowercase letter
                        </div>
                        <div class="requirement" data-requirement="number">
                            <span class="requirement-icon text-red-500">✗</span>
                            One number
                        </div>
                        <div class="requirement" data-requirement="special">
                            <span class="requirement-icon text-red-500">✗</span>
                            One special character
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                        id="submit-button"
                        disabled
                    >
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-purple-500 group-hover:text-purple-400 transition duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </span>
                        Reset Password
                    </button>
                </div>

                <!-- Back to Login -->
                <div class="text-center">
                    <a 
                        href="{{ route('superauth.auth.login') }}" 
                        class="text-sm text-purple-600 dark:text-purple-400 hover:text-purple-500 dark:hover:text-purple-300 font-medium transition duration-200"
                    >
                        ← Back to Login
                    </a>
                </div>
            </form>
        </div>

        <!-- Additional Help -->
        <div class="text-center">
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Remember your password? 
                <a href="{{ route('superauth.auth.login') }}" class="font-medium text-purple-600 dark:text-purple-400 hover:text-purple-500 dark:hover:text-purple-300 transition duration-200">
                    Sign in here
                </a>
            </p>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const strengthBar = document.querySelector('.password-strength-bar');
    const strengthText = document.querySelector('.password-strength-text');
    const requirements = document.querySelectorAll('.requirement');
    const submitButton = document.getElementById('submit-button');
    
    function checkPasswordStrength(password) {
        let score = 0;
        const checks = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /\d/.test(password),
            special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
        };
        
        // Update requirements
        Object.keys(checks).forEach(key => {
            const requirement = document.querySelector(`[data-requirement="${key}"]`);
            const icon = requirement.querySelector('.requirement-icon');
            
            if (checks[key]) {
                icon.textContent = '✓';
                icon.className = 'requirement-icon text-green-500';
                score++;
            } else {
                icon.textContent = '✗';
                icon.className = 'requirement-icon text-red-500';
            }
        });
        
        // Update strength bar
        const percentage = (score / 5) * 100;
        strengthBar.style.width = percentage + '%';
        
        if (score < 2) {
            strengthBar.className = 'password-strength-bar bg-red-500 h-2 rounded-full transition-all duration-300';
            strengthText.textContent = 'Weak';
            strengthText.className = 'password-strength-text text-sm font-medium text-red-600 dark:text-red-400';
        } else if (score < 4) {
            strengthBar.className = 'password-strength-bar bg-yellow-500 h-2 rounded-full transition-all duration-300';
            strengthText.textContent = 'Medium';
            strengthText.className = 'password-strength-text text-sm font-medium text-yellow-600 dark:text-yellow-400';
        } else {
            strengthBar.className = 'password-strength-bar bg-green-500 h-2 rounded-full transition-all duration-300';
            strengthText.textContent = 'Strong';
            strengthText.className = 'password-strength-text text-sm font-medium text-green-600 dark:text-green-400';
        }
        
        // Enable/disable submit button
        const passwordsMatch = password === confirmInput.value && password.length > 0;
        submitButton.disabled = !(score >= 3 && passwordsMatch);
    }
    
    function checkPasswordMatch() {
        const password = passwordInput.value;
        const confirm = confirmInput.value;
        const passwordsMatch = password === confirm && password.length > 0;
        
        if (confirm.length > 0) {
            if (passwordsMatch) {
                confirmInput.className = 'block w-full pl-10 pr-3 py-3 border border-green-300 dark:border-green-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-200';
            } else {
                confirmInput.className = 'block w-full pl-10 pr-3 py-3 border border-red-300 dark:border-red-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-200';
            }
        } else {
            confirmInput.className = 'block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-200';
        }
        
        checkPasswordStrength(password);
    }
    
    passwordInput.addEventListener('input', checkPasswordMatch);
    confirmInput.addEventListener('input', checkPasswordMatch);
});
</script>
@endpush
@endsection
