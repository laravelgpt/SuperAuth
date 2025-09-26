<?php

namespace SuperAuth\Tests\Feature;

use SuperAuth\Tests\TestCase;
use SuperAuth\Models\User;
use SuperAuth\Models\SocialAccount;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Mockery;

class SocialLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_google()
    {
        $mockUser = Mockery::mock('Laravel\Socialite\Two\User');
        $mockUser->shouldReceive('getId')->andReturn('123456789');
        $mockUser->shouldReceive('getName')->andReturn('John Doe');
        $mockUser->shouldReceive('getEmail')->andReturn('john@example.com');
        $mockUser->shouldReceive('getAvatar')->andReturn('https://example.com/avatar.jpg');

        Socialite::shouldReceive('driver->user')->andReturn($mockUser);

        $response = $this->get('/superauth/auth/social/google/callback');
        $response->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
        ]);

        $this->assertDatabaseHas('social_accounts', [
            'provider' => 'google',
            'provider_id' => '123456789',
        ]);
    }

    public function test_user_can_login_with_facebook()
    {
        $mockUser = Mockery::mock('Laravel\Socialite\Two\User');
        $mockUser->shouldReceive('getId')->andReturn('987654321');
        $mockUser->shouldReceive('getName')->andReturn('Jane Doe');
        $mockUser->shouldReceive('getEmail')->andReturn('jane@example.com');
        $mockUser->shouldReceive('getAvatar')->andReturn('https://example.com/avatar2.jpg');

        Socialite::shouldReceive('driver->user')->andReturn($mockUser);

        $response = $this->get('/superauth/auth/social/facebook/callback');
        $response->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'email' => 'jane@example.com',
        ]);
    }

    public function test_user_can_login_with_github()
    {
        $mockUser = Mockery::mock('Laravel\Socialite\Two\User');
        $mockUser->shouldReceive('getId')->andReturn('456789123');
        $mockUser->shouldReceive('getName')->andReturn('Dev User');
        $mockUser->shouldReceive('getEmail')->andReturn('dev@example.com');
        $mockUser->shouldReceive('getAvatar')->andReturn('https://example.com/avatar3.jpg');

        Socialite::shouldReceive('driver->user')->andReturn($mockUser);

        $response = $this->get('/superauth/auth/social/github/callback');
        $response->assertStatus(302);

        $this->assertDatabaseHas('users', [
            'email' => 'dev@example.com',
        ]);
    }

    public function test_user_can_connect_existing_account()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $mockUser = Mockery::mock('Laravel\Socialite\Two\User');
        $mockUser->shouldReceive('getId')->andReturn('111222333');
        $mockUser->shouldReceive('getEmail')->andReturn($user->email);

        Socialite::shouldReceive('driver->user')->andReturn($mockUser);

        $response = $this->get('/superauth/auth/social/google/callback');
        $response->assertStatus(302);

        $this->assertDatabaseHas('social_accounts', [
            'user_id' => $user->id,
            'provider' => 'google',
            'provider_id' => '111222333',
        ]);
    }

    public function test_user_can_disconnect_social_account()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $socialAccount = $user->socialAccounts()->create([
            'provider' => 'google',
            'provider_id' => '123456789',
            'provider_email' => $user->email,
        ]);

        $response = $this->delete("/superauth/user/social/{$socialAccount->id}");
        $response->assertStatus(302);

        $this->assertDatabaseMissing('social_accounts', [
            'id' => $socialAccount->id,
        ]);
    }
}
