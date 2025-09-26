<?php

namespace SuperAuth\Livewire\User;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use SuperAuth\Models\User;
use SuperAuth\Services\AiAgentService;

class Dashboard extends Component
{
    public $user;
    public $stats = [];
    public $recentActivity = [];
    public $securityAlerts = [];
    public $recommendations = [];

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $this->loadStats();
        $this->loadRecentActivity();
        $this->loadSecurityAlerts();
        $this->loadRecommendations();
    }

    protected function loadStats()
    {
        $this->stats = [
            'total_logins' => $this->user->loginHistories()->count(),
            'last_login' => $this->user->last_login_at?->diffForHumans(),
            'account_age' => $this->user->created_at->diffForHumans(),
            'security_score' => $this->user->security_score ?? 85,
            'risk_level' => $this->getRiskLevel($this->user->risk_score ?? 0),
        ];
    }

    protected function loadRecentActivity()
    {
        $this->recentActivity = $this->user->loginHistories()
            ->latest()
            ->limit(5)
            ->get()
            ->map(function ($login) {
                return [
                    'type' => $login->is_successful ? 'success' : 'failed',
                    'message' => $login->is_successful 
                        ? 'Successful login from ' . ($login->country ?? 'Unknown location')
                        : 'Failed login attempt',
                    'time' => $login->created_at->diffForHumans(),
                    'ip' => $login->ip_address,
                    'device' => $login->device_type,
                ];
            })
            ->toArray();
    }

    protected function loadSecurityAlerts()
    {
        $aiService = app(AiAgentService::class);
        $this->securityAlerts = $aiService->getSecurityAlerts();
    }

    protected function loadRecommendations()
    {
        $aiService = app(AiAgentService::class);
        $this->recommendations = $aiService->getRecommendations();
    }

    protected function getRiskLevel($score)
    {
        if ($score < 30) return 'low';
        if ($score < 70) return 'medium';
        return 'high';
    }

    public function refreshData()
    {
        $this->loadDashboardData();
        $this->dispatch('data-refreshed');
    }

    public function render()
    {
        $viewType = config('superauth.view_type', 'livewire');
        
        return match($viewType) {
            'laravel' => view('superauth::user.dashboard-blade'),
            'livewire' => view('superauth::livewire.user.dashboard'),
            'vue' => view('superauth::user.dashboard-vue'),
            'react-nextjs' => view('superauth::user.dashboard-react'),
            'custom' => view('superauth::user.dashboard-custom'),
            default => view('superauth::livewire.user.dashboard')
        };
    }
}
