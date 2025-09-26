@extends('superauth::shared.layouts.auth')

@section('title', 'Two-Factor Authentication')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-12 w-12 bg-gradient-to-r from-purple-600 to-blue-600 rounded-full flex items-center justify-center">
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
                Two-Factor Authentication
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Enter the 6-digit code from your authenticator app.
            </p>
        </div>

        <!-- Two-Factor Form -->
        <div class="bg-white dark:bg-gray-800 py-8 px-6 shadow-xl rounded-xl border border-gray-200 dark:border-gray-700">
            <form action="{{ route('superauth.auth.two-factor.verify') }}" method="POST" class="space-y-6">
                @csrf
                
                <!-- Code Input -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Authentication Code
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <input 
                            id="code" 
                            name="code" 
                            type="text" 
                            autocomplete="one-time-code" 
                            required 
                            maxlength="6"
                            pattern="[0-9]{6}"
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition duration-200 text-center text-2xl font-mono tracking-widest @error('code') border-red-500 @enderror"
                            placeholder="000000"
                            autofocus
                        >
                    </div>
                    @error('code')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Device -->
                <div class="flex items-center">
                    <input 
                        id="remember_device" 
                        name="remember_device" 
                        type="checkbox" 
                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded"
                    >
                    <label for="remember_device" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                        Remember this device for 30 days
                    </label>
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition duration-200 transform hover:scale-105"
                    >
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-purple-500 group-hover:text-purple-400 transition duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </span>
                        Verify Code
                    </button>
                </div>

                <!-- Recovery Options -->
                <div class="text-center space-y-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <p>Lost access to your authenticator app?</p>
                    </div>
                    
                    <div class="space-y-2">
                        <button 
                            type="button"
                            onclick="showRecoveryCode()"
                            class="text-sm text-purple-600 dark:text-purple-400 hover:text-purple-500 dark:hover:text-purple-300 font-medium transition duration-200"
                        >
                            Use recovery code instead
                        </button>
                        
                        <button 
                            type="button"
                            onclick="resendCode()"
                            class="block text-sm text-gray-600 dark:text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition duration-200"
                        >
                            Resend code via SMS
                        </button>
                    </div>
                </div>

                <!-- Back to Login -->
                <div class="text-center pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a 
                        href="{{ route('superauth.auth.login') }}" 
                        class="text-sm text-purple-600 dark:text-purple-400 hover:text-purple-500 dark:hover:text-purple-300 font-medium transition duration-200"
                    >
                        ← Back to Login
                    </a>
                </div>
            </form>
        </div>

        <!-- Recovery Code Modal (Hidden by default) -->
        <div id="recovery-code-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
                <div class="mt-3 text-center">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
                        Enter Recovery Code
                    </h3>
                    <form action="{{ route('superauth.auth.two-factor.recovery') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <input 
                                type="text" 
                                name="recovery_code" 
                                placeholder="Enter recovery code"
                                class="block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                required
                            >
                        </div>
                        <div class="flex space-x-3">
                            <button 
                                type="submit"
                                class="flex-1 bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition duration-200"
                            >
                                Verify
                            </button>
                            <button 
                                type="button"
                                onclick="hideRecoveryCode()"
                                class="flex-1 bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition duration-200"
                            >
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Auto-focus and format code input
document.getElementById('code').addEventListener('input', function(e) {
    // Remove non-numeric characters
    this.value = this.value.replace(/[^0-9]/g, '');
    
    // Limit to 6 digits
    if (this.value.length > 6) {
        this.value = this.value.slice(0, 6);
    }
    
    // Auto-submit when 6 digits are entered
    if (this.value.length === 6) {
        setTimeout(() => {
            this.form.submit();
        }, 500);
    }
});

// Recovery code functions
function showRecoveryCode() {
    document.getElementById('recovery-code-modal').classList.remove('hidden');
}

function hideRecoveryCode() {
    document.getElementById('recovery-code-modal').classList.add('hidden');
}

// Resend code function
function resendCode() {
    fetch('{{ route("superauth.auth.two-factor.resend") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Code sent successfully!');
        } else {
            alert('Failed to send code. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}

// Close modal when clicking outside
document.getElementById('recovery-code-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        hideRecoveryCode();
    }
});
</script>
@endpush
@endsection
