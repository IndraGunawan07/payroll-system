<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Factory::create();

        $employeeData = [];

        // ADMIN
        $employeeData[] = [
            'id' => Str::uuid(),
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'email' => "admin@dealls.com",
            'base_salary' => $faker->randomFloat(0, 1000, 10000),
            'is_admin' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        for ($i = 0; $i < 10; $i++) {
            $username = $faker->userName();

            $employeeData[] = [
                'id' => Str::uuid(),
                'username' => $username,
                'password' => Hash::make('password123'),
                'email' => $username . "@dealls.com",
                'base_salary' => $faker->randomFloat(0, 1000, 10000),
                'is_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        User::insert($employeeData);
    }
}
