<?php

namespace SuperAuth\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SuperAuth\Models\User;
use SuperAuth\Services\BreachCheckService;
use SuperAuth\Services\PasswordStrengthService;

class Register extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $showPassword = false;
    public $passwordStrength = 0;
    public $breachCount = 0;
    public $strengthRequirements = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
    ];

    public function updatedPassword()
    {
        if (strlen($this->password) > 0) {
            // Check password breach
            $this->breachCount = app(BreachCheckService::class)->checkPasswordBreach($this->password);
            
            // Calculate password strength
            $strength = app(PasswordStrengthService::class)->calculateStrength($this->password);
            $this->passwordStrength = $strength['score'];
            $this->strengthRequirements = $strength['requirements'];
        } else {
            $this->breachCount = 0;
            $this->passwordStrength = 0;
            $this->strengthRequirements = [];
        }
    }

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function register()
    {
        $this->validate();

        // Check password breach
        if ($this->breachCount > 0) {
            $this->addError('password', "This password has been found in {$this->breachCount} data breaches. Please choose a different password.");
            return;
        }

        // Check password strength
        if ($this->passwordStrength < 60) {
            $this->addError('password', 'Password is too weak. Please choose a stronger password.');
            return;
        }

        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        Auth::login($user);
        return redirect('/dashboard');
    }

    public function render()
    {
        return view('superauth::livewire.auth.register')
            ->layout('superauth::layouts.auth');
    }
}
