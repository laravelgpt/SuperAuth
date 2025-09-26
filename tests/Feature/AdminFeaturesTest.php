<?php

namespace SuperAuth\Tests\Feature;

use SuperAuth\Tests\TestCase;
use SuperAuth\Models\User;
use SuperAuth\Models\Role;
use SuperAuth\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create();
        $this->admin->assignRole('admin');
        
        $this->user = User::factory()->create();
        $this->user->assignRole('user');
    }

    public function test_admin_can_access_dashboard()
    {
        $this->actingAs($this->admin);
        
        $response = $this->get('/superauth/admin/dashboard');
        $response->assertStatus(200);
    }

    public function test_admin_can_view_users()
    {
        $this->actingAs($this->admin);
        
        $response = $this->get('/superauth/admin/users');
        $response->assertStatus(200);
    }

    public function test_admin_can_create_user()
    {
        $this->actingAs($this->admin);
        
        $userData = [
            'name' => 'New User',
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
        
        $response = $this->post('/superauth/admin/users', $userData);
        $response->assertStatus(302);
        
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
        ]);
    }

    public function test_admin_can_assign_roles()
    {
        $this->actingAs($this->admin);
        
        $response = $this->post("/superauth/admin/users/{$this->user->id}/roles", [
            'roles' => ['moderator'],
        ]);
        
        $response->assertStatus(302);
        $this->assertTrue($this->user->fresh()->hasRole('moderator'));
    }

    public function test_admin_can_manage_roles()
    {
        $this->actingAs($this->admin);
        
        $response = $this->get('/superauth/admin/roles');
        $response->assertStatus(200);
    }

    public function test_admin_can_create_role()
    {
        $this->actingAs($this->admin);
        
        $roleData = [
            'name' => 'editor',
            'display_name' => 'Editor',
            'description' => 'Content editor role',
            'level' => 50,
        ];
        
        $response = $this->post('/superauth/admin/roles', $roleData);
        $response->assertStatus(302);
        
        $this->assertDatabaseHas('roles', [
            'name' => 'editor',
        ]);
    }

    public function test_regular_user_cannot_access_admin()
    {
        $this->actingAs($this->user);
        
        $response = $this->get('/superauth/admin/dashboard');
        $response->assertStatus(403);
    }
}
