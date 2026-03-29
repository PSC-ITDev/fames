<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Division;
use App\Models\Department;
use App\Models\Role;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        //Create Divisions
        //Create Department
        //Create Roles
        //Create Users



    }
}
