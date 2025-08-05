<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_id' => 1,
            'email' => $this->faker->email,
            'first_name' => $this->faker->name,
            'last_name' => $this->faker->name,
            'password' => Hash::make('password'),
            'gender' => 1,
            'birthday' => '1990-01-01',
            'address' => 'homeless',
            'avatar' => 'empty',
            'salary' => '100',
            'position' => 5,
            'status' => 1,
            'type_of_work' => 1,
            'ins_id' => 1,
        ];
    }
}
