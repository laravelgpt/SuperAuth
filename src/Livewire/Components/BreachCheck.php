<?php

namespace SuperAuth\Livewire\Components;

use Livewire\Component;
use SuperAuth\Services\BreachCheckService;

class BreachCheck extends Component
{
    public $password = '';
    public $showPassword = false;
    public $isChecking = false;
    public $breachCount = 0;
    public $lastChecked = null;
    public $apiResponseTime = null;
    public $cacheStatus = 'Fresh';

    protected $listeners = ['passwordUpdated' => 'checkBreach'];

    public function mount($label = null, $placeholder = null, $required = false)
    {
        $this->label = $label;
        $this->placeholder = $placeholder;
        $this->required = $required;
    }

    public function updatedPassword()
    {
        if (strlen($this->password) > 0) {
            $this->checkBreach();
        } else {
            $this->resetBreachData();
        }
    }

    public function checkBreach()
    {
        if (empty($this->password)) {
            $this->resetBreachData();
            return;
        }

        $this->isChecking = true;
        
        try {
            $startTime = microtime(true);
            
            $breachService = app(BreachCheckService::class);
            $result = $breachService->checkPassword($this->password);
            
            $this->apiResponseTime = round((microtime(true) - $startTime) * 1000);
            $this->breachCount = $result['breach_count'] ?? 0;
            $this->lastChecked = now()->format('Y-m-d H:i:s');
            $this->cacheStatus = $result['cached'] ? 'Cached' : 'Fresh';
            
        } catch (\Exception $e) {
            $this->breachCount = 0;
            $this->lastChecked = now()->format('Y-m-d H:i:s');
            $this->cacheStatus = 'Error';
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
        $this->lastChecked = null;
        $this->apiResponseTime = null;
        $this->cacheStatus = 'Fresh';
    }

    public function render()
    {
        return view('livewire.components.breach-check');
    }
}
