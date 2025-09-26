<?php

namespace SuperAuth\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use SuperAuth\Services\SecureLoggingService;
use SuperAuth\Services\EnhancedPasswordStrengthService;
use SuperAuth\Services\EnhancedBreachCheckService;

class ResetPassword extends Component
{
    public $token;
    public $email;
    public $password = '';
    public $password_confirmation = '';
    public $isLoading = false;
    public $passwordStrength = 0;
    public $passwordRequirements = [];
    public $breachCheckResult = null;

    protected $rules = [
        'password' => 'required|min:8|confirmed',
        'password_confirmation' => 'required',
    ];

    protected $messages = [
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least 8 characters.',
        'password.confirmed' => 'Password confirmation does not match.',
        'password_confirmation.required' => 'Password confirmation is required.',
    ];

    public function mount($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'password') {
            $this->checkPasswordStrength();
            $this->checkPasswordBreach();
        }
        
        $this->validateOnly($propertyName);
    }

    public function checkPasswordStrength()
    {
        if (empty($this->password)) {
            $this->passwordStrength = 0;
            $this->passwordRequirements = [];
            return;
        }

        $strengthService = app(EnhancedPasswordStrengthService::class);
        $result = $strengthService->analyzePassword($this->password);
        
        $this->passwordStrength = $result['score'];
        $this->passwordRequirements = $result['requirements'];
    }

    public function checkPasswordBreach()
    {
        if (empty($this->password)) {
            $this->breachCheckResult = null;
            return;
        }

        try {
            $breachService = app(EnhancedBreachCheckService::class);
            $result = $breachService->checkPassword($this->password);
            
            $this->breachCheckResult = [
                'is_breached' => $result['is_breached'],
                'breach_count' => $result['breach_count'],
                'message' => $result['message']
            ];
        } catch (\Exception $e) {
            $this->breachCheckResult = [
                'is_breached' => false,
                'breach_count' => 0,
                'message' => 'Unable to check password breach status'
            ];
        }
    }

    public function resetPassword()
    {
        $this->validate();

        // Rate limiting
        $key = 'reset-password:' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('password', 'Too many password reset attempts. Please try again later.');
            return;
        }

        // Check password strength
        if ($this->passwordStrength < 60) {
            $this->addError('password', 'Password is too weak. Please choose a stronger password.');
            return;
        }

        // Check for password breach
        if ($this->breachCheckResult && $this->breachCheckResult['is_breached']) {
            $this->addError('password', 'This password has been found in data breaches. Please choose a different password.');
            return;
        }

        $this->isLoading = true;

        try {
            // Reset password
            $status = Password::reset([
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ], function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            });

            if ($status === Password::PASSWORD_RESET) {
                // Log successful password reset
                app(SecureLoggingService::class)->logInfo('Password reset successful', [
                    'email' => $this->email,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);

                session()->flash('status', 'Your password has been reset successfully.');
                return redirect()->route('superauth.auth.login');
            } else {
                $this->addError('password', 'Invalid or expired reset token.');
            }
        } catch (\Exception $e) {
            $this->addError('password', 'An error occurred while resetting your password. Please try again.');
            
            // Log the error
            app(SecureLoggingService::class)->logError('Password reset failed', [
                'email' => $this->email,
                'error' => $e->getMessage(),
                'ip' => request()->ip(),
            ]);
        }

        RateLimiter::hit($key, 300); // 5 minutes
        $this->isLoading = false;
    }

    public function render()
    {
        return view('superauth::features.authentication.reset-password')
            ->layout('superauth::shared.layouts.auth');
    }
}
