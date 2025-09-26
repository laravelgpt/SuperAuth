<?php

namespace SuperAuth\Livewire\Components;

use Livewire\Component;
use SuperAuth\Services\EnhancedPasswordStrengthService;

class EnhancedPasswordStrength extends Component
{
    public $password = '';
    public $showPassword = false;
    public $strength = 0;
    public $requirements = [];
    public $recommendations = [];
    public $breachCount = 0;
    public $entropy = 0;
    public $uniqueChars = 0;
    public $patternScore = 0;
    public $complexity = 'Low';
    public $uppercaseCount = 0;
    public $lowercaseCount = 0;
    public $numberCount = 0;
    public $symbolCount = 0;

    protected $listeners = ['passwordUpdated' => 'analyzePassword'];

    public function mount($label = null, $placeholder = null, $required = false)
    {
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->required = $required;
    }

    public function updatedPassword()
    {
        if (strlen($this->password) > 0) {
            $this->analyzePassword();
        } else {
            $this->resetAnalysis();
        }
    }

    public function analyzePassword()
    {
        if (empty($this->password)) {
            $this->resetAnalysis();
            return;
        }

        try {
            $strengthService = app(EnhancedPasswordStrengthService::class);
            $result = $strengthService->analyzePassword($this->password);
            
            $this->strength = $result['strength'] ?? 0;
            $this->requirements = $result['requirements'] ?? [];
            $this->recommendations = $result['recommendations'] ?? [];
            $this->breachCount = $result['breach_count'] ?? 0;
            $this->entropy = $result['entropy'] ?? 0;
            $this->uniqueChars = $result['unique_chars'] ?? 0;
            $this->patternScore = $result['pattern_score'] ?? 0;
            $this->complexity = $result['complexity'] ?? 'Low';
            $this->uppercaseCount = $result['uppercase_count'] ?? 0;
            $this->lowercaseCount = $result['lowercase_count'] ?? 0;
            $this->numberCount = $result['number_count'] ?? 0;
            $this->symbolCount = $result['symbol_count'] ?? 0;
            
        } catch (\Exception $e) {
            $this->resetAnalysis();
        }
    }

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    private function resetAnalysis()
    {
        $this->strength = 0;
        $this->requirements = [];
        $this->recommendations = [];
        $this->breachCount = 0;
        $this->entropy = 0;
        $this->uniqueChars = 0;
        $this->patternScore = 0;
        $this->complexity = 'Low';
        $this->uppercaseCount = 0;
        $this->lowercaseCount = 0;
        $this->numberCount = 0;
        $this->symbolCount = 0;
    }

    public function render()
    {
        return view('livewire.components.enhanced-password-strength');
    }
}
