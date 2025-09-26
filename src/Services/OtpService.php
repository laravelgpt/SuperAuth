<?php

namespace SuperAuth\Services;

use SuperAuth\Models\OtpVerification;
use SuperAuth\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use SuperAuth\Mail\OtpMail;

class OtpService
{
    protected $maxAttempts = 3;
    protected $decayMinutes = 15;
    protected $otpLength = 6;
    protected $expiryMinutes = 10;

    public function __construct()
    {
        $this->maxAttempts = config('superauth.otp.max_attempts', 3);
        $this->decayMinutes = config('superauth.otp.decay_minutes', 15);
        $this->otpLength = config('superauth.otp.length', 6);
        $this->expiryMinutes = config('superauth.otp.expiry_minutes', 10);
    }

    public function generateOtp($userId, $purpose = 'verification', $expiresIn = null)
    {
        $expiresIn = $expiresIn ?: $this->expiryMinutes;
        
        // Check rate limiting
        if ($this->isRateLimited($userId, $purpose)) {
            throw new \Exception('Too many OTP requests. Please try again later.');
        }

        // Invalidate existing OTPs for this user and purpose
        $this->invalidateExistingOtps($userId, $purpose);

        // Generate new OTP
        $otp = $this->createOtpCode();
        
        // Create OTP record
        $otpRecord = OtpVerification::createOtp($userId, $purpose, $expiresIn);
        
        // Update rate limiting
        $this->updateRateLimit($userId, $purpose);
        
        return $otpRecord;
    }

    public function sendOtp($userId, $purpose = 'verification', $expiresIn = null)
    {
        $otpRecord = $this->generateOtp($userId, $purpose, $expiresIn);
        $user = User::find($userId);
        
        if (!$user) {
            throw new \Exception('User not found');
        }

        // Send OTP via email
        $this->sendOtpEmail($user, $otpRecord);
        
        return $otpRecord;
    }

    public function verifyOtp($otp, $userId, $purpose = 'verification')
    {
        $otpRecord = OtpVerification::where('otp', $otp)
            ->where('user_id', $userId)
            ->where('purpose', $purpose)
            ->valid()
            ->first();

        if (!$otpRecord) {
            $this->recordFailedAttempt($userId, $purpose);
            return false;
        }

        if ($otpRecord->hasExceededMaxAttempts()) {
            $this->recordFailedAttempt($userId, $purpose);
            return false;
        }

        // Mark as verified
        $otpRecord->markAsVerified();
        
        // Clear rate limiting
        $this->clearRateLimit($userId, $purpose);
        
        return true;
    }

    public function resendOtp($userId, $purpose = 'verification')
    {
        // Check if user has active OTP
        if (OtpVerification::hasActiveOtp($userId, $purpose)) {
            throw new \Exception('Active OTP already exists. Please wait before requesting a new one.');
        }

        return $this->sendOtp($userId, $purpose);
    }

    public function validateOtp($otp, $userId, $purpose = 'verification')
    {
        $otpRecord = OtpVerification::where('otp', $otp)
            ->where('user_id', $userId)
            ->where('purpose', $purpose)
            ->valid()
            ->first();

        if (!$otpRecord) {
            return false;
        }

        return $otpRecord->isValid();
    }

    public function getOtpStatus($userId, $purpose = 'verification')
    {
        $otpRecord = OtpVerification::getOtpByUser($userId, $purpose);
        
        if (!$otpRecord) {
            return [
                'status' => 'none',
                'message' => 'No active OTP',
            ];
        }

        if ($otpRecord->isExpired()) {
            return [
                'status' => 'expired',
                'message' => 'OTP has expired',
                'expires_at' => $otpRecord->expires_at,
            ];
        }

        if ($otpRecord->isUsed()) {
            return [
                'status' => 'used',
                'message' => 'OTP has been used',
                'used_at' => $otpRecord->used_at,
            ];
        }

        if ($otpRecord->isVerified()) {
            return [
                'status' => 'verified',
                'message' => 'OTP has been verified',
                'verified_at' => $otpRecord->verified_at,
            ];
        }

        return [
            'status' => 'active',
            'message' => 'OTP is active',
            'expires_at' => $otpRecord->expires_at,
            'attempts_remaining' => $otpRecord->getAttemptsRemaining(),
        ];
    }

    public function getOtpStats($userId = null)
    {
        if ($userId) {
            return OtpVerification::getOtpSummary($userId);
        }
        
        return OtpVerification::getOtpStats();
    }

    public function getOtpTrends($days = 30)
    {
        return OtpVerification::getOtpTrends($days);
    }

    public function getRecentOtps($userId, $limit = 5)
    {
        return OtpVerification::getRecentOtps($userId, $limit);
    }

    public function cleanupExpiredOtps()
    {
        return OtpVerification::cleanupExpiredOtps();
    }

    public function getOtpAnalytics($days = 30)
    {
        return [
            'stats' => $this->getOtpStats(),
            'trends' => $this->getOtpTrends($days),
            'top_purposes' => $this->getTopPurposes($days),
            'success_rate' => $this->getSuccessRate($days),
            'average_attempts' => $this->getAverageAttempts($days),
        ];
    }

    protected function createOtpCode()
    {
        return str_pad(random_int(0, pow(10, $this->otpLength) - 1), $this->otpLength, '0', STR_PAD_LEFT);
    }

    protected function sendOtpEmail($user, $otpRecord)
    {
        try {
            Mail::to($user->email)->send(new OtpMail($user, $otpRecord));
        } catch (\Exception $e) {
            throw new \Exception('Failed to send OTP email: ' . $e->getMessage());
        }
    }

    protected function invalidateExistingOtps($userId, $purpose)
    {
        OtpVerification::where('user_id', $userId)
            ->where('purpose', $purpose)
            ->whereNull('used_at')
            ->where('is_verified', false)
            ->update(['used_at' => now()]);
    }

    protected function recordFailedAttempt($userId, $purpose)
    {
        $otpRecord = OtpVerification::getOtpByUser($userId, $purpose);
        if ($otpRecord) {
            $otpRecord->incrementAttempts();
        }
    }

    protected function isRateLimited($userId, $purpose)
    {
        $key = "otp_rate_limit:{$userId}:{$purpose}";
        return RateLimiter::tooManyAttempts($key, $this->maxAttempts);
    }

    protected function updateRateLimit($userId, $purpose)
    {
        $key = "otp_rate_limit:{$userId}:{$purpose}";
        RateLimiter::hit($key, $this->decayMinutes * 60);
    }

    protected function clearRateLimit($userId, $purpose)
    {
        $key = "otp_rate_limit:{$userId}:{$purpose}";
        RateLimiter::clear($key);
    }

    protected function getTopPurposes($days)
    {
        return OtpVerification::where('created_at', '>=', now()->subDays($days))
            ->selectRaw('purpose, COUNT(*) as count')
            ->groupBy('purpose')
            ->orderBy('count', 'desc')
            ->get();
    }

    protected function getSuccessRate($days)
    {
        $total = OtpVerification::where('created_at', '>=', now()->subDays($days))->count();
        $verified = OtpVerification::where('created_at', '>=', now()->subDays($days))
            ->where('is_verified', true)
            ->count();
        
        return $total > 0 ? round(($verified / $total) * 100, 2) : 0;
    }

    protected function getAverageAttempts($days)
    {
        return OtpVerification::where('created_at', '>=', now()->subDays($days))
            ->avg('attempts') ?? 0;
    }

    public function getOtpConfig()
    {
        return [
            'max_attempts' => $this->maxAttempts,
            'decay_minutes' => $this->decayMinutes,
            'otp_length' => $this->otpLength,
            'expiry_minutes' => $this->expiryMinutes,
        ];
    }

    public function updateOtpConfig($config)
    {
        $this->maxAttempts = $config['max_attempts'] ?? $this->maxAttempts;
        $this->decayMinutes = $config['decay_minutes'] ?? $this->decayMinutes;
        $this->otpLength = $config['otp_length'] ?? $this->otpLength;
        $this->expiryMinutes = $config['expiry_minutes'] ?? $this->expiryMinutes;
        
        return true;
    }

    public function getOtpPurposes()
    {
        return [
            'verification' => 'Email Verification',
            'login' => 'Login Verification',
            'password_reset' => 'Password Reset',
            'two_factor' => 'Two-Factor Authentication',
            'security' => 'Security Verification',
        ];
    }

    public function getOtpStatuses()
    {
        return [
            'none' => 'No Active OTP',
            'active' => 'Active',
            'expired' => 'Expired',
            'used' => 'Used',
            'verified' => 'Verified',
        ];
    }
}
