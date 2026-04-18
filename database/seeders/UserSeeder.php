<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Admin Master',
                'email' => 'admin@toko.com',
                'role' => 'admin',
            ],
            [
                'name' => 'Kasir 1',
                'email' => 'kasir1@toko.com',
                'role' => 'kasir',
            ],
            [
                'name' => 'Kasir 2',
                'email' => 'kasir2@toko.com',
                'role' => 'kasir',
            ],
            [
                'name' => 'Owner',
                'email' => 'owner@toko.com',
                'role' => 'owner',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('password'),
                    'role' => $user['role'],
                ]
            );
        }
    }
}