<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'iamnehalpatel@gmail.com',
                'password' => bcrypt('nehal123'),
                'remember_token' => null,
            ],
            [
                'name' => 'Nehal Patel',
                'email' => 'nehal.sdjic@gmail.com',
                'password' => bcrypt('nehal123'),
                'remember_token' => null,
            ],
        ];

        User::insert($users);
    }
}
