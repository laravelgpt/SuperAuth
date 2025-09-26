<?php

namespace SuperAuth\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use SuperAuth\Services\SecureLoggingService;

class ForgotPassword extends Component
{
    public $email = '';
    public $status = '';
    public $isLoading = false;

    protected $rules = [
        'email' => 'required|email|max:255',
    ];

    protected $messages = [
        'email.required' => 'Email address is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.max' => 'Email address must not exceed 255 characters.',
    ];

    public function mount()
    {
        $this->email = old('email', '');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function sendResetLink()
    {
        $this->validate();

        // Rate limiting
        $key = 'forgot-password:' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('email', 'Too many password reset attempts. Please try again later.');
            return;
        }

        $this->isLoading = true;

        try {
            // Send password reset link
            $status = Password::sendResetLink(['email' => $this->email]);

            if ($status === Password::RESET_LINK_SENT) {
                $this->status = 'We have emailed your password reset link.';
                $this->email = '';
                
                // Log the password reset request
                app(SecureLoggingService::class)->logInfo('Password reset link sent', [
                    'email' => $this->email,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            } else {
                $this->addError('email', 'We could not find a user with that email address.');
            }
        } catch (\Exception $e) {
            $this->addError('email', 'An error occurred while sending the reset link. Please try again.');
            
            // Log the error
            app(SecureLoggingService::class)->logError('Password reset link failed', [
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
        return view('superauth::features.authentication.forgot-password')
            ->layout('superauth::shared.layouts.auth');
    }
}
