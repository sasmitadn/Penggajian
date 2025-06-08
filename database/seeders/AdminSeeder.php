<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $roles = include config_path('role.php');
        $permissions = Arr::flatten($roles);

        Category::create([
            'name' => 'Admin',
            'type' => 'job_category',
            'status' => 'active',
            'role' => json_encode($permissions),
            'is_paid' => 0,
            'price_daily' => 0.00,
            'price_overtime' => 0.00,
            'work_start' => null,
            'work_end' => null
        ]);

        Category::create([
            'name' => 'Cutting',
            'type' => 'job_category',
            'status' => 'active',
            'role' => json_encode($permissions),
            'is_paid' => 1,
            'price_daily' => 100000.00,
            'price_overtime' => 20000.00,
            'work_start' => '08:00',
            'work_end' => '17:00'
        ]);

        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'id_category' => 1, // Assuming the first category is Admin
            'status' => 'active'
        ]);

        for ($i = 0; $i < 0; $i++) {
            User::factory()->create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'address' => fake()->address(),
                'password' => Hash::make('password'),
                'id_category' => 2, // Assuming the first category is Admin
                'status' => 'active'
            ]);
        }
    }
}
