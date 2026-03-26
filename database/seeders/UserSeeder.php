<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Gestionnaire ISI',
            'email'    => 'admin@isiburger.com',
            'password' => Hash::make('password'),
            'role'     => 'gestionnaire',
        ]);

        User::create([
            'name'     => 'Client Test',
            'email'    => 'client@isiburger.com',
            'password' => Hash::make('password'),
            'role'     => 'client',
        ]);
    }
}
