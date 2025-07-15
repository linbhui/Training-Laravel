<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        Team::factory()->create([
            'name' => 'Team Red',
            'ins_id' => 1,
            'del_flag' => 0
        ]);

        Team::factory()->create([
            'name' => 'Team Blue',
            'ins_id' => 1,
            'del_flag' => 0
        ]);

        Team::factory()->create([
            'name' => 'Team White',
            'ins_id' => 1,
            'del_flag' => 0
        ]);

    }
}

