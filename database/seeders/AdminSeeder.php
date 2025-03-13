<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin4gmail.com',
            'password' => Hash::make('admin123'), // Default password
            'role' => 'admin',
            'must_change_password' => true, // Force password change on first login
            'email_verified_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
