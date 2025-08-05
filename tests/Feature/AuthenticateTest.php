<?php

namespace Feature;

use App\Models\Employee;
use Tests\TestCase;

class AuthenticateTest extends TestCase
{

    public function test_render_login_screen(): void
    {
        $response = $this->get('/management/login');

        $response->assertOk();
    }

    public function test_user_can_authenticate(): void
    {
        $user = Employee::factory()->create();

        $response = $this->post('/management/login', [
                'email' => $user->email,
                'password' => 'password'
            ]);

        $response->assertRedirectToRoute('management.dashboard');

        $this->assertAuthenticated('employees');
    }

    public function test_user_authenticate_deactivated_account(): void
    {
        $user = Employee::factory()->create([
            'del_flag' => 1
        ]);

        $response = $this->post('/management/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertRedirectBackWithErrors([
            'email' => 'Your account is deactivated.'
        ]);

        $this->assertGuest();
    }

    public function test_user_authenticate_invalid_credentials(): void
    {
        $user = Employee::factory()->create();

        $response = $this->post('/management/login', [
                'email' => $user->email,
                'password' => 'wrong-password'
            ]);

        $response->assertRedirectBackWithErrors([
            'email' => 'Invalid email/password'
        ]);

        $this->assertGuest();
    }

    public function test_user_can_logout():void
    {
        $user = Employee::factory()->create();

        $response = $this->actingAs($user, 'employees')
            ->post('/management/logout');

        $response->assertRedirectToRoute('management.showLogin');

        $this->assertGuest();
    }
}

// php artisan test --filter=AuthenticateTest
