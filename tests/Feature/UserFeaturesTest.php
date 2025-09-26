<?php

namespace SuperAuth\Tests\Feature;

use SuperAuth\Tests\TestCase;
use SuperAuth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_user_can_view_profile()
    {
        $response = $this->get('/superauth/user/profile');
        $response->assertStatus(200);
        $response->assertSee($this->user->name);
    }

    public function test_user_can_update_profile()
    {
        $response = $this->put('/superauth/user/profile', [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    public function test_user_can_change_password()
    {
        $response = $this->put('/superauth/user/password', [
            'current_password' => 'password',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(Hash::check('newpassword123', $this->user->fresh()->password));
    }

    public function test_user_can_view_security_settings()
    {
        $response = $this->get('/superauth/user/security');
        $response->assertStatus(200);
    }

    public function test_user_can_view_login_history()
    {
        $response = $this->get('/superauth/user/login-history');
        $response->assertStatus(200);
    }

    public function test_user_can_enable_two_factor()
    {
        $response = $this->post('/superauth/user/two-factor/enable');
        $response->assertStatus(200);
    }

    public function test_user_can_disable_two_factor()
    {
        $response = $this->post('/superauth/user/two-factor/disable');
        $response->assertStatus(200);
    }

    public function test_user_can_delete_account()
    {
        $response = $this->delete('/superauth/user/account', [
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('users', [
            'id' => $this->user->id,
        ]);
    }
}
