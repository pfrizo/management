<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'department_id' => 1,   // Adm
            'name' => 'Admin',
            'email' => 'admin@management.com',
            'email_verified_at' => now(),
            'password' => bcrypt('Aa123456'),
            'role' => 'admin',
            'permissions' => '["admin"]',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('user_details')->insert([
            'user_id' => 1,
            'address' => 'Admin Street, 123',
            'zip_code' => '1234-123',
            'city' => 'London',
            'phone' => '900000001',
            'salary' => 8000.00,
            'admission_date' => '2020-01-01',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('departments')->insert([
            'name' => 'Administration',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('departments')->insert([
            'name' => 'Human Resources',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
