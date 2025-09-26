<?php

namespace SuperAuth\Tests\Feature;

use SuperAuth\Tests\TestCase;
use SuperAuth\Models\User;
use SuperAuth\Models\Role;
use SuperAuth\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/superauth/auth/register', $userData);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
            'name' => 'Test User',
        ]);
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->post('/superauth/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(302);
        $this->assertAuthenticated();
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/superauth/auth/logout');

        $response->assertStatus(302);
        $this->assertGuest();
    }

    public function test_user_can_reset_password()
    {
        $user = User::factory()->create();

        $response = $this->post('/superauth/auth/forgot-password', [
            'email' => $user->email,
        ]);

        $response->assertStatus(302);
    }

    public function test_user_can_change_password()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put('/superauth/user/password', [
            'current_password' => 'password',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertStatus(302);
        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    public function test_user_can_update_profile()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put('/superauth/user/profile', [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);
    }

    public function test_user_can_delete_account()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->delete('/superauth/user/account', [
            'password' => 'password',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    public function test_user_can_connect_social_account()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/superauth/auth/social/google', [
            'provider_id' => '123456789',
            'provider_email' => 'test@gmail.com',
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_id' => '123456789',
        ]);
    }

    public function test_user_can_disconnect_social_account()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $socialAccount = $user->socialAccounts()->create([
            'provider' => 'google',
            'provider_id' => '123456789',
            'provider_email' => 'test@gmail.com',
        ]);

        $response = $this->delete("/superauth/user/social/{$socialAccount->id}");

        $response->assertStatus(302);
        $this->assertDatabaseMissing('social_accounts', [
            'id' => $socialAccount->id,
        ]);
    }

    public function test_user_can_verify_otp()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $otp = $user->generateOtp('verification');

        $response = $this->post('/superauth/auth/verify-otp', [
            'otp' => $otp,
            'purpose' => 'verification',
        ]);

        $response->assertStatus(200);
        $this->assertTrue($user->verifyOtp($otp, 'verification'));
    }

    public function test_user_can_request_otp()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/superauth/auth/request-otp', [
            'purpose' => 'verification',
        ]);

        $response->assertStatus(200);
        $this->assertTrue($user->otpVerifications()->where('purpose', 'verification')->exists());
    }
}
