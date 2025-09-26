<?php

namespace SuperAuth\Tests\Feature;

use SuperAuth\Tests\TestCase;
use SuperAuth\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MobileResponsivenessTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_is_mobile_responsive()
    {
        $response = $this->get('/superauth/auth/login');
        $response->assertStatus(200);
        $response->assertSee('viewport');
        $response->assertSee('mobile');
    }

    public function test_register_page_is_mobile_responsive()
    {
        $response = $this->get('/superauth/auth/register');
        $response->assertStatus(200);
        $response->assertSee('viewport');
        $response->assertSee('mobile');
    }

    public function test_dashboard_is_mobile_responsive()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/superauth/user/dashboard');
        $response->assertStatus(200);
        $response->assertSee('viewport');
        $response->assertSee('mobile');
    }

    public function test_profile_page_is_mobile_responsive()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/superauth/user/profile');
        $response->assertStatus(200);
        $response->assertSee('viewport');
        $response->assertSee('mobile');
    }

    public function test_admin_dashboard_is_mobile_responsive()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        $response = $this->get('/superauth/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('viewport');
        $response->assertSee('mobile');
    }

    public function test_error_pages_are_mobile_responsive()
    {
        $response = $this->get('/superauth/errors/404');
        $response->assertStatus(200);
        $response->assertSee('viewport');
        $response->assertSee('mobile');
    }
}
