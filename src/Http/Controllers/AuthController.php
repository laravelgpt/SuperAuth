<?php

namespace SuperAuth\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use SuperAuth\Models\User;
use SuperAuth\Models\OtpVerification;
use SuperAuth\Services\BreachCheckService;
use SuperAuth\Services\PasswordStrengthService;
use SuperAuth\Services\AiAgentService;

class AuthController extends Controller
{
    protected $breachService;
    protected $passwordService;
    protected $aiService;

    public function __construct(
        BreachCheckService $breachService,
        PasswordStrengthService $passwordService,
        AiAgentService $aiService
    ) {
        $this->breachService = $breachService;
        $this->passwordService = $passwordService;
        $this->aiService = $aiService;
    }

    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('superauth::auth.login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            
            // Log login with AI analysis
            $this->aiService->logLogin($user, [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'is_successful' => true,
            ]);

            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Show registration form
     */
    public function showRegister()
    {
        return view('superauth::auth.register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Check password breach
        $breachCount = $this->breachService->checkPasswordBreach($request->password);
        if ($breachCount > 0) {
            return back()->withErrors([
                'password' => "This password has been found in {$breachCount} data breaches. Please choose a different password.",
            ])->withInput();
        }

        // Check password strength
        $strength = $this->passwordService->calculateStrength($request->password);
        if ($strength['score'] < 60) {
            return back()->withErrors([
                'password' => 'Password is too weak. Please choose a stronger password.',
            ])->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Send email verification
        $this->sendEmailVerification($user);

        Auth::login($user);
        return redirect('/dashboard');
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Show password reset form
     */
    public function showPasswordReset()
    {
        return view('superauth::auth.forgot-password');
    }

    /**
     * Send password reset link
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $token = Str::random(64);
            $user->update(['remember_token' => $token]);
            
            Mail::send('superauth::emails.password-reset', [
                'user' => $user,
                'token' => $token,
                'url' => route('superauth.password.reset', $token)
            ], function ($message) use ($user) {
                $message->to($user->email)->subject('Password Reset');
            });
        }

        return back()->with('status', 'If your email exists, we have sent a password reset link.');
    }

    /**
     * Show password reset form with token
     */
    public function showPasswordResetForm($token)
    {
        return view('superauth::auth.reset-password', ['token' => $token]);
    }

    /**
     * Handle password reset
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)
            ->where('remember_token', $request->token)
            ->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Invalid reset token.']);
        }

        // Check password breach
        $breachCount = $this->breachService->checkPasswordBreach($request->password);
        if ($breachCount > 0) {
            return back()->withErrors([
                'password' => "This password has been found in {$breachCount} data breaches. Please choose a different password.",
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'remember_token' => null,
        ]);

        return redirect('/login')->with('status', 'Password reset successfully.');
    }

    /**
     * Send OTP
     */
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        $otp = rand(100000, 999999);
        OtpVerification::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::send('superauth::emails.otp', [
            'user' => $user,
            'otp' => $otp,
        ], function ($message) use ($user) {
            $message->to($user->email)->subject('Your OTP Code');
        });

        return back()->with('status', 'OTP sent to your email.');
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'User not found.']);
        }

        $otpRecord = OtpVerification::where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        $otpRecord->delete();
        Auth::login($user);
        return redirect('/dashboard');
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        
        // Delete existing OTPs
        OtpVerification::where('email', $request->email)->delete();
        
        return $this->sendOtp($request);
    }

    /**
     * Send email verification
     */
    protected function sendEmailVerification(User $user)
    {
        $token = Str::random(64);
        $user->update(['email_verification_token' => $token]);
        
        Mail::send('superauth::emails.verify-email', [
            'user' => $user,
            'url' => route('superauth.email.verify', $token)
        ], function ($message) use ($user) {
            $message->to($user->email)->subject('Verify Your Email');
        });
    }

    /**
     * Verify email
     */
    public function verifyEmail($token)
    {
        $user = User::where('email_verification_token', $token)->first();
        
        if ($user) {
            $user->update([
                'email_verified_at' => now(),
                'email_verification_token' => null,
            ]);
            return redirect('/login')->with('status', 'Email verified successfully.');
        }
        
        return redirect('/login')->with('error', 'Invalid verification token.');
    }

    /**
     * Dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();
        return view('superauth::dashboard', compact('user'));
    }

    /**
     * API Login
     */
    public function apiLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('auth-token')->plainTextToken;
            
            // Log login with AI analysis
            $this->aiService->logLogin($user, [
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'is_successful' => true,
            ]);

            return response()->json([
                'user' => $user,
                'token' => $token,
                'message' => 'Login successful'
            ]);
        }

        return response()->json([
            'error' => 'Invalid credentials'
        ], 401);
    }

    /**
     * API Register
     */
    public function apiRegister(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // Check password breach
        $breachCount = $this->breachService->checkPasswordBreach($request->password);
        if ($breachCount > 0) {
            return response()->json([
                'error' => "Password found in {$breachCount} data breaches",
                'breach_count' => $breachCount
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'Registration successful'
        ], 201);
    }

    /**
     * API Logout
     */
    public function apiLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        
        return response()->json([
            'message' => 'Logout successful'
        ]);
    }
}
