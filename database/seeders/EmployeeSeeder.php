<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        Employee::firstOrCreate(
            ['email' => 'manager@gmail.com'],
            [
                'team_id' => 1,
                'first_name' => 'Jane',
                'last_name' => 'Manager',
                'password' => Hash::make('123'),
                'gender' => 2,
                'birthday' => '1990-01-01',
                'address' => '123 This St, That City',
                'avatar' => 'mana.jpg',
                'salary' => 10000,
                'position' => 1,
                'status' => 1,
                'type_of_work' => 1,
                'ins_id' => 1,
                'del_flag' => 0
            ]
        );

        Employee::firstOrCreate(
            ['email' => 'leader@gmail.com'],
            [
                'team_id' => 1,
                'first_name' => 'John',
                'last_name' => 'Leader',
                'password' => Hash::make('123'),
                'gender' => 1,
                'birthday' => '1995-01-01',
                'address' => '321 That St, This City',
                'avatar' => 'lead.jpg',
                'salary' => 7000,
                'position' => 2,
                'status' => 1,
                'type_of_work' => 1,
                'ins_id' => 1,
                'del_flag' => 0
            ]
        );

        Employee::firstOrCreate(
            ['email' => 'bse@gmail.com'],
            [
                'team_id' => 1,
                'first_name' => 'Jamie',
                'last_name' => 'BSE',
                'password' => Hash::make('123'),
                'gender' => 2,
                'birthday' => '1998-01-01',
                'address' => '456 These St, Those City',
                'avatar' => 'bse.jpg',
                'salary' => 5000,
                'position' => 3,
                'status' => 1,
                'type_of_work' => 3,
                'ins_id' => 1,
                'del_flag' => 0
            ]
        );

        Employee::firstOrCreate(
            ['email' => 'dev@gmail.com'],
            [
                'team_id' => 1,
                'first_name' => 'Jose',
                'last_name' => 'Dev',
                'password' => Hash::make('123'),
                'gender' => 1,
                'birthday' => '1999-01-01',
                'address' => '789 They St, Them City',
                'avatar' => 'dev.jpg',
                'salary' => 3000,
                'position' => 4,
                'status' => 1,
                'type_of_work' => 2,
                'ins_id' => 1,
                'del_flag' => 0
            ]
        );

        Employee::firstOrCreate(
            ['email' => 'intern@gmail.com'],
            [
                'team_id' => 1,
                'first_name' => 'Jim',
                'last_name' => 'Intern',
                'password' => Hash::make('123'),
                'gender' => 1,
                'birthday' => '1999-01-01',
                'address' => '789 Those St, These City',
                'avatar' => 'int.jpg',
                'salary' => 1000,
                'position' => 5,
                'status' => 1,
                'type_of_work' => 4,
                'ins_id' => 1,
                'del_flag' => 0
            ]
        );
    }
}
