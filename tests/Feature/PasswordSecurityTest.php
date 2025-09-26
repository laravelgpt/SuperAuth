<?php

namespace SuperAuth\Tests\Feature;

use SuperAuth\Tests\TestCase;
use SuperAuth\Models\User;
use SuperAuth\Services\BreachCheckService;
use SuperAuth\Services\PasswordStrengthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class PasswordSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_breach_check_works()
    {
        Http::fake([
            'api.pwnedpasswords.com/range/*' => Http::response('5D41402ABC4B2A76B9719D911017C592:3', 200),
        ]);

        $service = app(BreachCheckService::class);
        $result = $service->check('hello');

        $this->assertEquals(3, $result);
    }

    public function test_password_strength_analysis()
    {
        $service = app(PasswordStrengthService::class);
        
        $weakPassword = $service->analyze('123');
        $strongPassword = $service->analyze('MyStr0ng!P@ssw0rd');

        $this->assertLessThan(50, $weakPassword['score']);
        $this->assertGreaterThan(80, $strongPassword['score']);
    }

    public function test_password_requirements_validation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/superauth/user/password', [
            'current_password' => 'password',
            'password' => 'weak',
            'password_confirmation' => 'weak',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_breach_check_integration()
    {
        Http::fake([
            'api.pwnedpasswords.com/range/*' => Http::response('5D41402ABC4B2A76B9719D911017C592:1000', 200),
        ]);

        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/superauth/user/password', [
            'current_password' => 'password',
            'password' => 'hello',
            'password_confirmation' => 'hello',
        ]);

        $response->assertSessionHasErrors(['password']);
    }

    public function test_password_strength_visual_feedback()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/superauth/user/profile');
        $response->assertStatus(200);
        $response->assertSee('password-strength');
    }
}
