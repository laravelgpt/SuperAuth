<?php

namespace SuperAuth\Livewire\Profile;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class Profile extends Component
{
    public $profileForm = [];
    public $currentPassword = '';
    public $newPassword = '';
    public $newPasswordConfirmation = '';
    public $showCurrentPassword = false;
    public $showNewPassword = false;
    public $passwordStrength = 0;
    public $socialAccounts = [];

    protected $rules = [
        'profileForm.name' => 'required|string|max:255',
        'profileForm.email' => 'required|email|max:255',
        'profileForm.phone' => 'nullable|string|max:20',
        'profileForm.date_of_birth' => 'nullable|date|before:today',
        'profileForm.bio' => 'nullable|string|max:1000',
        'profileForm.city' => 'nullable|string|max:100',
        'profileForm.country' => 'nullable|string|max:2',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->profileForm = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'date_of_birth' => $user->date_of_birth,
            'bio' => $user->bio,
            'city' => $user->city,
            'country' => $user->country,
        ];
        
        $this->socialAccounts = $user->socialAccounts ?? [];
    }

    public function updatedNewPassword()
    {
        if (!empty($this->newPassword)) {
            $this->passwordStrength = $this->calculatePasswordStrength($this->newPassword);
        } else {
            $this->passwordStrength = 0;
        }
    }

    public function saveProfile()
    {
        $this->validate();
        
        $user = Auth::user();
        $user->update($this->profileForm);
        
        session()->flash('success', 'Profile updated successfully!');
    }

    public function changePassword()
    {
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();
        
        if (!Hash::check($this->currentPassword, $user->password)) {
            $this->addError('currentPassword', 'Current password is incorrect.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->newPassword)
        ]);

        $this->currentPassword = '';
        $this->newPassword = '';
        $this->newPasswordConfirmation = '';
        $this->passwordStrength = 0;
        
        session()->flash('success', 'Password changed successfully!');
    }

    public function toggleCurrentPasswordVisibility()
    {
        $this->showCurrentPassword = !$this->showCurrentPassword;
    }

    public function toggleNewPasswordVisibility()
    {
        $this->showNewPassword = !$this->showNewPassword;
    }

    public function disconnectAccount($accountId)
    {
        $account = $this->socialAccounts->find($accountId);
        if ($account) {
            $account->delete();
            $this->socialAccounts = Auth::user()->socialAccounts ?? [];
            session()->flash('success', 'Social account disconnected successfully!');
        }
    }

    public function exportData()
    {
        $user = Auth::user();
        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at,
            'profile' => $this->profileForm,
            'social_accounts' => $this->socialAccounts,
        ];
        
        // In a real implementation, you would generate and download a file
        session()->flash('success', 'Data export initiated. You will receive an email with your data shortly.');
    }

    public function deleteAccount()
    {
        $user = Auth::user();
        $user->delete();
        
        Auth::logout();
        return redirect()->route('superauth.login')->with('success', 'Your account has been deleted.');
    }

    private function calculatePasswordStrength($password)
    {
        $score = 0;
        $length = strlen($password);
        
        // Length scoring
        if ($length >= 8) $score += 20;
        if ($length >= 12) $score += 10;
        if ($length >= 16) $score += 10;
        
        // Character variety
        if (preg_match('/[a-z]/', $password)) $score += 10;
        if (preg_match('/[A-Z]/', $password)) $score += 10;
        if (preg_match('/[0-9]/', $password)) $score += 10;
        if (preg_match('/[^a-zA-Z0-9]/', $password)) $score += 20;
        
        // Pattern penalties
        if (preg_match('/(.)\1{2,}/', $password)) $score -= 10;
        if (preg_match('/123|abc|qwe/i', $password)) $score -= 15;
        
        return min(100, max(0, $score));
    }

    public function render()
    {
        return view('livewire.profile.profile');
    }
}
