<?php

namespace SuperAuth\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use SuperAuth\Services\SecureLoggingService;

class VerifyEmail extends Component
{
    public $email = '';
    public $status = '';
    public $isLoading = false;

    public function mount()
    {
        $this->email = Auth::user()->email ?? '';
    }

    public function resendVerification()
    {
        $this->isLoading = true;

        // Rate limiting
        $key = 'verify-email:' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('email', 'Too many verification attempts. Please try again later.');
            $this->isLoading = false;
            return;
        }

        try {
            if (Auth::user() && !Auth::user()->hasVerifiedEmail()) {
                Auth::user()->sendEmailVerificationNotification();
                
                $this->status = 'Verification email sent successfully!';
                
                // Log the verification email sent
                app(SecureLoggingService::class)->logInfo('Email verification sent', [
                    'user_id' => Auth::id(),
                    'email' => Auth::user()->email,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            } else {
                $this->addError('email', 'Email is already verified or user not found.');
            }
        } catch (\Exception $e) {
            $this->addError('email', 'Failed to send verification email. Please try again.');
            
            // Log the error
            app(SecureLoggingService::class)->logError('Email verification failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'ip' => request()->ip(),
            ]);
        }

        RateLimiter::hit($key, 300); // 5 minutes
        $this->isLoading = false;
    }

    public function render()
    {
        return view('superauth::features.authentication.verify-email')
            ->layout('superauth::shared.layouts.auth');
    }
}
