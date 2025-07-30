<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function run(): void
    {
        Team::firstOrCreate(
            ['name' => 'Team Red'],
            ['ins_id' => 1, 'del_flag' => 0]
        );

        Team::firstOrCreate(
            ['name' => 'Team Blue'],
            ['ins_id' => 1, 'del_flag' => 0]
        );

        Team::firstOrCreate(
            ['name' => 'Team White'],
            ['ins_id' => 1, 'del_flag' => 0]
        );
    }
}
