<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        // Admin inicial
        User::create([
            'name' => 'Admin Inicial',
            'email' => 'andre.cainda.524@outlook.pt',
            'password' => Hash::make('12345678'),
            'role_id' => 1, // Admin
        ]);

        // Cidadão inicial
        User::create([
            'name' => 'Cidadão Teste',
            'email' => 'cidadao@example.com',
            'password' => Hash::make('password'),
            'role_id' => 2, // Cidadão
        ]);
    }
}
