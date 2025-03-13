<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() {
        $users = [
            [
                'name' => 'Alice Johnson',
                'email' => 'alice@example.com',
                'phone' => '601136408532',
                'password' => Hash::make('password'),
                'role'=>'admin',
                'email_verified_at' => null,
                'phone_verified_at' => null, 
            ],
            [
                'name' => 'Bob Smith',
                'email' => 'bob@example.com',
                'phone' => '60129876543',
                'password' => Hash::make('password123'),
                'email_verified_at' => null,
                'phone_verified_at' => null,
            ],
            [
                'name' => 'Charlie Brown',
                'email' => 'charlie@example.com',
                'phone' => '60137654321',
                'password' => Hash::make('password123'),
                'email_verified_at' => null,
                'phone_verified_at' => null,
            ],
            [
                'name' => 'David Lee',
                'email' => 'david@example.com',
                'phone' => '60148765432',
                'password' => Hash::make('password123'),
                'email_verified_at' => null,
                'phone_verified_at' => null,
            ],
            [
                'name' => 'Emma Watson',
                'email' => 'emma@example.com',
                'phone' => '60156789012',
                'password' => Hash::make('password123'),
                'email_verified_at' => null,
                'phone_verified_at' => null,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
 
    }
}
