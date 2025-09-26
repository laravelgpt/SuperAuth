<?php

namespace SuperAuth\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use SuperAuth\Models\User;
use SuperAuth\Models\Role;
use SuperAuth\Models\Permission;
use SuperAuth\Services\AiAgentService;
use SuperAuth\Services\MultiChannelNotificationService;

class AdminController extends Controller
{
    protected $aiService;
    protected $notificationService;

    public function __construct(AiAgentService $aiService, MultiChannelNotificationService $notificationService)
    {
        $this->aiService = $aiService;
        $this->notificationService = $notificationService;
    }

    /**
     * Admin dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'admin_users' => User::where('is_admin', true)->count(),
            'recent_logins' => User::where('last_login_at', '>=', now()->subDays(7))->count(),
        ];

        return view('superauth::admin.dashboard', compact('stats'));
    }

    /**
     * User management
     */
    public function users()
    {
        $users = User::with('roles')->paginate(15);
        return view('superauth::admin.users', compact('users'));
    }

    /**
     * Show user details
     */
    public function showUser(User $user)
    {
        $user->load(['roles', 'permissions', 'socialAccounts']);
        return view('superauth::admin.users.show', compact('user'));
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'is_active' => 'boolean',
            'is_admin' => 'boolean',
        ]);

        $user->update($request->only(['name', 'email', 'is_active', 'is_admin']));

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    /**
     * Delete user
     */
    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user status
     */
    public function toggleUserStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "User {$status} successfully.");
    }

    /**
     * Role management
     */
    public function roles()
    {
        $roles = Role::with('permissions')->paginate(15);
        return view('superauth::admin.roles', compact('roles'));
    }

    /**
     * Create role
     */
    public function createRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string',
            'permissions' => 'array',
        ]);

        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        if ($request->permissions) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->back()->with('success', 'Role created successfully.');
    }

    /**
     * Update role
     */
    public function updateRole(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
            'permissions' => 'array',
        ]);

        $role->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->back()->with('success', 'Role updated successfully.');
    }

    /**
     * Delete role
     */
    public function deleteRole(Role $role)
    {
        $role->delete();
        return redirect()->back()->with('success', 'Role deleted successfully.');
    }

    /**
     * User roles
     */
    public function userRoles(User $user)
    {
        $roles = Role::all();
        $userRoles = $user->roles;
        return view('superauth::admin.users.roles', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Assign role to user
     */
    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $role = Role::findOrFail($request->role_id);
        $user->assignRoleWithExpiration($role, $request->expires_at, auth()->user());

        return redirect()->back()->with('success', 'Role assigned successfully.');
    }

    /**
     * Remove role from user
     */
    public function removeRole(User $user, Role $role)
    {
        $user->revokeRole($role);
        return redirect()->back()->with('success', 'Role removed successfully.');
    }

    /**
     * AI Dashboard
     */
    public function aiDashboard()
    {
        $insights = $this->aiService->generateInsights();
        $anomalies = $this->aiService->getAnomalies();
        $recommendations = $this->aiService->getRecommendations();

        return view('superauth::admin.ai-dashboard', compact('insights', 'anomalies', 'recommendations'));
    }

    /**
     * Analytics
     */
    public function analytics()
    {
        $userAnalytics = [
            'total_users' => User::count(),
            'new_users_this_month' => User::where('created_at', '>=', now()->startOfMonth())->count(),
            'active_users' => User::where('is_active', true)->count(),
            'admin_users' => User::where('is_admin', true)->count(),
        ];

        return view('superauth::admin.analytics', compact('userAnalytics'));
    }

    /**
     * Security monitoring
     */
    public function securityMonitoring()
    {
        $threats = $this->aiService->getSecurityThreats();
        $incidents = $this->aiService->getSecurityIncidents();
        
        return view('superauth::admin.security-monitoring', compact('threats', 'incidents'));
    }

    /**
     * API endpoints
     */
    public function apiUsers()
    {
        $users = User::with('roles')->paginate(15);
        return response()->json($users);
    }

    public function apiShowUser(User $user)
    {
        $user->load(['roles', 'permissions', 'socialAccounts']);
        return response()->json($user);
    }

    public function apiUpdateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'is_active' => 'boolean',
            'is_admin' => 'boolean',
        ]);

        $user->update($request->only(['name', 'email', 'is_active', 'is_admin']));
        return response()->json(['message' => 'User updated successfully', 'user' => $user]);
    }

    public function apiDeleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'You cannot delete your own account'], 400);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    public function apiToggleUserStatus(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json(['error' => 'You cannot deactivate your own account'], 400);
        }

        $user->update(['is_active' => !$user->is_active]);
        return response()->json(['message' => 'User status updated', 'user' => $user]);
    }
}
