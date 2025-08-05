<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Team;
use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TeamTest extends TestCase
{
    public function test_render_team_management_screen(): void
    {
        $user = Employee::factory()->create();

        $response = $this->actingAs($user, 'employees')
            ->get(route('team.index'));

        $response->assertOk();
        $response->assertViewHas('teams');
    }

    public function test_sorted_team_table(): void
    {
        $user = Employee::factory()->create();

        foreach (['asc', 'desc'] as $sort) {
            $response = $this->actingAs($user, 'employees')
                ->get(route('team.index', ['sort' => $sort]));

            $response->assertOk();
            $response->assertViewHas('teams', function ($teams) use ($sort) {
                $names = $teams->pluck('name')->toArray();

                $sorted = $names;
                $sort === 'asc' ? sort($sorted) : rsort($sorted);

                return $names === $sorted;
            });
        }
    }

    public function test_render_add_team_screen() : void
    {
        $user = Employee::factory()->create();

        $response = $this->actingAs($user, 'employees')
            ->get(route('team.add'));

        $response->assertOk();
    }

    public function test_add_new_team_success(): void
    {
        $user = Employee::factory()->create();
        $faker = Factory::create();
        $new = 'Team ' . $faker->name;

        $response = $this->actingAs($user, 'employees')
            ->withSession(['id' => $user->id])
            ->post(route('team.add_confirm'), [
                'name' => $new
            ]);

        $this->assertDatabaseHas('teams', [
            'name' => $new,
            'ins_id' => $user->id
        ]);

        $response->assertRedirectToRoute('team.index');
        $response->assertSessionHas('notification', 'New team added successfully.');
    }

    public function test_add_new_team_fail(): void
    {
        $user = Employee::factory()->create();
        $exist = Team::first();
        $name = 'Team ' . $exist->name;

        $response = $this->actingAs($user, 'employees')
            ->withSession(['id' => $user->id])
            ->post(route('team.add_confirm'), [
                'name' => $name
            ]);

        $response->assertRedirectBackWithErrors('name');
    }

    public function test_render_edit_team_screen() : void
    {
        $user = Employee::factory()->create();
        $team = Team::factory()->create();

        $response = $this->actingAs($user, 'employees')
            ->get(route('team.edit', [$team->id]));

        $response->assertOk();
        $response->assertSee($team->name);
    }

    public function test_edit_team_success(): void
    {
        $user = Employee::factory()->create();
        $team = Team::factory()->create();
        $faker = Factory::create();
        $new = "Team " . $faker->name;

        $response = $this->actingAs($user, 'employees')
            ->withSession(['id' => $user->id])
            ->post(route('team.edit_confirm'), [
                'id' => $team->id,
                'name' => $new
            ]);

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'name' => $new,
            'upd_id' => $user->id
        ]);

        $response->assertRedirectToRoute('team.index');
        $response->assertSessionHas('notification', 'Updated team successfully.');
    }

    public function test_edit_team_fail(): void
    {
        $user = Employee::factory()->create();
        $team = Team::factory()->create();
        $exist = Team::first();
        $name = 'Team ' . $exist->name;

        $response = $this->actingAs($user, 'employees')
            ->withSession(['id' => $user->id])
            ->post(route('team.edit_confirm'), [
                'id' => $team->id,
                'name' => $name
            ]);

        $response->assertRedirectBackWithErrors('name');
    }

    public function test_delete_team(): void
    {
        $user = Employee::factory()->create();
        $team = Team::factory()->create();

        $response = $this->actingAs($user, 'employees')
            ->get(route('team.delete', [$team->id]));

        $response->assertRedirectToRoute('team.index');
        $response->assertSessionHas('notification', 'Deleted team successfully.');
        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'name' => $team->name,
            'del_flag' => 1
        ]);
    }

    public function test_recover_team(): void
    {
        $user = Employee::factory()->create();
        $team = Team::factory()->create([
            'del_flag' => 1
        ]);

        $response = $this->actingAs($user, 'employees')
            ->get(route('team.recover', [$team->id]));

        $response->assertRedirectToRoute('team.index');
        $response->assertSessionHas('notification', 'Recovered team successfully.');
        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'name' => $team->name,
            'del_flag' => 0
        ]);
    }

    public function test_search_team_by_name(): void
    {
        $user = Employee::factory()->create();

        $response = $this->actingAs($user, 'employees')
            ->get(route('team.search', [
                'by' => 'name',
                'search' => 'a'
            ]));

        $response->assertOk();
        $response->assertViewHas('teams');

    }

    public function test_search_team_by_group(): void
    {
        $user = Employee::factory()->create();

        foreach (['active', 'deactivated'] as $by) {
            $response = $this->actingAs($user, 'employees')
                ->get(route('team.search', [
                    'by' => 'group',
                    'search' => $by
                ]));

            $response->assertOk();
            $response->assertViewHas('teams');
        }
    }

    public function test_team_creator_and_updater_relationships(): void
    {
        $creator = Employee::factory()->create();
        $updater = Employee::factory()->create();

        $team = Team::factory()->create([
            'ins_id' => $creator->id,
            'upd_id' => $updater->id,
            'ins_datetime' => now(),
            'upd_datetime' => now(),
            'del_flag' => 0,
        ]);

        $employees = Employee::factory()->count(3)->create([
            'team_id' => $team->id,
        ]);

        $this->assertCount(3, $team->employees);
        foreach ($employees as $employee) {
            $this->assertTrue($team->employees->contains($employee));
        }

        $this->assertInstanceOf(Employee::class, $team->creator);
        $this->assertTrue($team->creator->is($creator));

        $this->assertInstanceOf(Employee::class, $team->updater);
        $this->assertTrue($team->updater->is($updater));
    }
}

// php artisan test --coverage --filter=test_team_creator_and_updater_relationships
