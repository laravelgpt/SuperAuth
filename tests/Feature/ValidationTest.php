<?php

namespace SuperAuth\Tests\Feature;

use SuperAuth\Tests\TestCase;
use SuperAuth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ValidationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_validation()
    {
        $response = $this->post('/superauth/auth/register', []);
        $response->assertSessionHasErrors(['name', 'email', 'password']);

        $response = $this->post('/superauth/auth/register', [
            'name' => 'Test User',
            'email' => 'invalid-email',
            'password' => '123',
            'password_confirmation' => '456',
        ]);
        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_login_validation()
    {
        $response = $this->post('/superauth/auth/login', []);
        $response->assertSessionHasErrors(['email', 'password']);

        $response = $this->post('/superauth/auth/login', [
            'email' => 'invalid-email',
            'password' => '',
        ]);
        $response->assertSessionHasErrors(['email', 'password']);
    }

    public function test_password_validation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put('/superauth/user/password', [
            'current_password' => 'wrong-password',
            'password' => '123',
            'password_confirmation' => '456',
        ]);
        $response->assertSessionHasErrors(['current_password', 'password']);
    }

    public function test_profile_validation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->put('/superauth/user/profile', [
            'name' => '',
            'email' => 'invalid-email',
        ]);
        $response->assertSessionHasErrors(['name', 'email']);
    }

    public function test_otp_validation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/superauth/auth/verify-otp', [
            'otp' => '123',
            'purpose' => '',
        ]);
        $response->assertSessionHasErrors(['purpose']);
    }

    public function test_social_account_validation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/superauth/auth/social/google', [
            'provider_id' => '',
            'provider_email' => 'invalid-email',
        ]);
        $response->assertSessionHasErrors(['provider_id', 'provider_email']);
    }
}
