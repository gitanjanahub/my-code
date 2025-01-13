<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample users
        $users = [
            [
                'name' => 'Steve Rogers',
                'email' => 'steve.rogers@example.com',
                'password' => Hash::make('password123'), // Use bcrypt to hash the password
            ],
            [
                'name' => 'Tony Stark',
                'email' => 'tony.stark@example.com',
                'password' => Hash::make('password123'),
            ],
            [
                'name' => 'Natasha Romanoff',
                'email' => 'natasha.romanoff@example.com',
                'password' => Hash::make('password123'),
            ],
        ];

        // Insert the users into the database
        foreach ($users as $user) {
            User::create($user);
        }
    }
}
