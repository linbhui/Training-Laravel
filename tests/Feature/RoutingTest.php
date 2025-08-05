<?php


namespace Tests\Feature;

use App\Models\Employee;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoutingTest extends TestCase
{
    protected function setUp():void
    {
        parent::setUp();

        Artisan::call('migrate', [
           '--path' =>  'database/migrations/base',
            '--env' => 'testing'
        ]);

        $this->seed(DatabaseSeeder::class);

        Artisan::call('migrate', [
            '--path' =>  'database/migrations/constraints',
            '--env' => 'testing'
        ]);

    }
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/management');

        $response->assertRedirectToRoute('management.showLogin');
    }

    public function test_user_is_redirected_to_dashboard(): void
    {
        $user = Employee::factory()->create();
        $response = $this->actingAs($user, 'employees')
            ->get('/management/login');

        $response->assertRedirect(route('management.dashboard'));
    }

    public function test_user_can_route(): void
    {
        $user = Employee::factory()->create();
        $response = $this->actingAs($user, 'employees')
            ->get('/management');
        $response->assertOk();
    }

}

// php artisan test --filter=RoutingTest
