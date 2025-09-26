<?php

namespace SuperAuth\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Hash;
use SuperAuth\Services\SecureLoggingService;
use SuperAuth\Services\OtpService;

class TwoFactor extends Component
{
    public $code = '';
    public $recoveryCode = '';
    public $rememberDevice = false;
    public $isLoading = false;
    public $showRecoveryCode = false;
    public $status = '';

    protected $rules = [
        'code' => 'required|string|size:6',
        'recoveryCode' => 'required|string|size:8',
    ];

    protected $messages = [
        'code.required' => 'Authentication code is required.',
        'code.size' => 'Authentication code must be 6 digits.',
        'recoveryCode.required' => 'Recovery code is required.',
        'recoveryCode.size' => 'Recovery code must be 8 characters.',
    ];

    public function updated($propertyName)
    {
        if ($propertyName === 'code') {
            // Remove non-numeric characters
            $this->code = preg_replace('/[^0-9]/', '', $this->code);
            
            // Limit to 6 digits
            if (strlen($this->code) > 6) {
                $this->code = substr($this->code, 0, 6);
            }
            
            // Auto-verify when 6 digits are entered
            if (strlen($this->code) === 6) {
                $this->verifyCode();
            }
        }
        
        $this->validateOnly($propertyName);
    }

    public function verifyCode()
    {
        $this->validate(['code' => 'required|string|size:6']);

        // Rate limiting
        $key = 'two-factor:' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('code', 'Too many verification attempts. Please try again later.');
            return;
        }

        $this->isLoading = true;

        try {
            $user = Auth::user();
            $otpService = app(OtpService::class);
            
            if ($otpService->verify($this->code, $user->two_factor_secret)) {
                // Successful verification
                if ($this->rememberDevice) {
                    // Set remember device cookie
                    cookie()->queue('two_factor_remember', encrypt($user->id), 30 * 24 * 60); // 30 days
                }
                
                // Log successful 2FA
                app(SecureLoggingService::class)->logInfo('Two-factor authentication successful', [
                    'user_id' => $user->id,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'remember_device' => $this->rememberDevice,
                ]);
                
                $this->status = 'Verification successful! Redirecting...';
                
                // Redirect to intended page
                return redirect()->intended('/dashboard');
            } else {
                $this->addError('code', 'Invalid authentication code. Please try again.');
                
                // Log failed 2FA attempt
                app(SecureLoggingService::class)->logWarning('Two-factor authentication failed', [
                    'user_id' => $user->id,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }
        } catch (\Exception $e) {
            $this->addError('code', 'An error occurred during verification. Please try again.');
            
            // Log the error
            app(SecureLoggingService::class)->logError('Two-factor authentication error', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'ip' => request()->ip(),
            ]);
        }

        RateLimiter::hit($key, 300); // 5 minutes
        $this->isLoading = false;
    }

    public function verifyRecoveryCode()
    {
        $this->validate(['recoveryCode' => 'required|string|size:8']);

        // Rate limiting
        $key = 'two-factor-recovery:' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $this->addError('recoveryCode', 'Too many recovery attempts. Please try again later.');
            return;
        }

        $this->isLoading = true;

        try {
            $user = Auth::user();
            
            // Check if recovery code is valid
            $recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);
            
            if (in_array($this->recoveryCode, $recoveryCodes)) {
                // Remove used recovery code
                $recoveryCodes = array_diff($recoveryCodes, [$this->recoveryCode]);
                $user->update([
                    'two_factor_recovery_codes' => encrypt(json_encode(array_values($recoveryCodes)))
                ]);
                
                // Log successful recovery
                app(SecureLoggingService::class)->logInfo('Two-factor recovery code used', [
                    'user_id' => $user->id,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
                
                $this->status = 'Recovery successful! Redirecting...';
                
                // Redirect to intended page
                return redirect()->intended('/dashboard');
            } else {
                $this->addError('recoveryCode', 'Invalid recovery code. Please try again.');
                
                // Log failed recovery attempt
                app(SecureLoggingService::class)->logWarning('Two-factor recovery failed', [
                    'user_id' => $user->id,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
            }
        } catch (\Exception $e) {
            $this->addError('recoveryCode', 'An error occurred during recovery. Please try again.');
            
            // Log the error
            app(SecureLoggingService::class)->logError('Two-factor recovery error', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'ip' => request()->ip(),
            ]);
        }

        RateLimiter::hit($key, 300); // 5 minutes
        $this->isLoading = false;
    }

    public function resendCode()
    {
        // Rate limiting
        $key = 'two-factor-resend:' . request()->ip();
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $this->addError('code', 'Too many resend attempts. Please try again later.');
            return;
        }

        try {
            $user = Auth::user();
            
            // Send SMS code if user has phone number
            if ($user->phone_number) {
                // Send SMS code logic here
                $this->status = 'Code sent via SMS!';
            } else {
                $this->addError('code', 'No phone number associated with your account.');
            }
            
            // Log resend attempt
            app(SecureLoggingService::class)->logInfo('Two-factor code resent', [
                'user_id' => $user->id,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            $this->addError('code', 'Failed to resend code. Please try again.');
            
            // Log the error
            app(SecureLoggingService::class)->logError('Two-factor resend failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'ip' => request()->ip(),
            ]);
        }

        RateLimiter::hit($key, 300); // 5 minutes
    }

    public function toggleRecoveryCode()
    {
        $this->showRecoveryCode = !$this->showRecoveryCode;
        $this->code = '';
        $this->recoveryCode = '';
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('superauth::features.authentication.two-factor')
            ->layout('superauth::shared.layouts.auth');
    }
}
