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
    public function run(): void
    {
        User::create([
            'name' => 'manager',
            'email' => 'manager@softxpert.com',
            'password' => Hash::make('password'),
            'role' => 'manager'
        ]);

        User::create([
            'name' => 'user 1',
            'email' => 'user1@softxpert.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);

        User::create([
            'name' => 'user 2',
            'email' => 'user2@softxpert.com',
            'password' => Hash::make('password'),
            'role' => 'user'
        ]);
    }
}
