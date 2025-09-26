<?php

namespace SuperAuth\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use SuperAuth\Services\AiAgentService;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;
    public $showPassword = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function login()
    {
        $this->validate();

        // Rate limiting
        $key = 'login.' . $this->email;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('email', 'Too many login attempts. Please try again later.');
            return;
        }

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $user = Auth::user();
            
            // Log login with AI analysis
            app(AiAgentService::class)->logLogin($user, [
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'is_successful' => true,
            ]);

            RateLimiter::clear($key);
            session()->regenerate();
            
            return redirect()->intended('/dashboard');
        }

        RateLimiter::hit($key, 300);
        $this->addError('email', 'The provided credentials do not match our records.');
    }

    public function render()
    {
        return view('superauth::livewire.auth.login')
            ->layout('superauth::layouts.auth');
    }
}
