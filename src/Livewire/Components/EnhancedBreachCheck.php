<?php

namespace SuperAuth\Livewire\Components;

use Livewire\Component;
use SuperAuth\Services\EnhancedBreachCheckService;

class EnhancedBreachCheck extends Component
{
    public $password = '';
    public $showPassword = false;
    public $isChecking = false;
    public $breachCount = 0;
    public $securityScore = 0;
    public $riskLevel = 'Low';
    public $confidence = 0;
    public $lastChecked = null;
    public $apiResponseTime = null;
    public $cacheStatus = 'Fresh';
    public $recommendations = [];

    protected $listeners = ['passwordUpdated' => 'checkEnhancedBreach'];

    public function mount($label = null, $placeholder = null, $required = false)
    {
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->required = $required;
    }

    public function updatedPassword()
    {
        if (strlen($this->password) > 0) {
            $this->checkEnhancedBreach();
        } else {
            $this->resetBreachData();
        }
    }

    public function checkEnhancedBreach()
    {
        if (empty($this->password)) {
            $this->resetBreachData();
            return;
        }

        $this->isChecking = true;
        
        try {
            $startTime = microtime(true);
            
            $breachService = app(EnhancedBreachCheckService::class);
            $result = $breachService->checkPassword($this->password);
            
            $this->apiResponseTime = round((microtime(true) - $startTime) * 1000);
            $this->breachCount = $result['breach_count'] ?? 0;
            $this->securityScore = $result['security_score'] ?? 0;
            $this->riskLevel = $result['risk_level'] ?? 'Low';
            $this->confidence = $result['confidence'] ?? 0;
            $this->lastChecked = now()->format('Y-m-d H:i:s');
            $this->cacheStatus = $result['cached'] ? 'Cached' : 'Fresh';
            $this->recommendations = $result['recommendations'] ?? [];
            
        } catch (\Exception $e) {
            $this->breachCount = 0;
            $this->securityScore = 0;
            $this->riskLevel = 'Low';
            $this->confidence = 0;
            $this->lastChecked = now()->format('Y-m-d H:i:s');
            $this->cacheStatus = 'Error';
            $this->recommendations = ['Unable to perform security analysis'];
        } finally {
            $this->isChecking = false;
        }
    }

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    private function resetBreachData()
    {
        $this->breachCount = 0;
        $this->securityScore = 0;
        $this->riskLevel = 'Low';
        $this->confidence = 0;
        $this->lastChecked = null;
        $this->apiResponseTime = null;
        $this->cacheStatus = 'Fresh';
        $this->recommendations = [];
    }

    public function render()
    {
        return view('livewire.components.enhanced-breach-check');
    }
}
