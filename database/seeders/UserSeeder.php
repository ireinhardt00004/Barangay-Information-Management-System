<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'lname' => 'Doe',
                'middlename' => 'A',
                'fname' => 'John',
                'email' => 'admin@gmail.com',
                'roles' => 'admin',
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // Default password
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'lname' => 'Smith',
                'middlename' => 'B',
                'fname' => 'Jane',
                'email' => 'jane.smith@example.com',
                'roles' => 'staff',
                'email_verified_at' => now(),
                'password' => Hash::make('password'), // Default password
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more user data as needed
        ]);
    }
}
