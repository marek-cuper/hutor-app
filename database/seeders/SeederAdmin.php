<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeederAdmin extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::insert([
            [
                'id' => 1,
                'name' => 'admin',
                'email' => 'admin@gmail.com',
                'password' => '$2y$10$5sTNA0e19sSNWlO9UK/qpe2yeK1XqACSVsQlwUixzC5o6Nm/h3PAa'
            ]

        ]);

        \App\Models\User_moderator::insert([
            [
                'user_id' => 1,
                'admin' => true
            ]

        ]);
    }
}
