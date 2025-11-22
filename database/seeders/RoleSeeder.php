<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['nome' => 'admin']);
        Role::firstOrCreate(['nome' => 'cidadao']);

       /*
       Role::firstOrCreate(
            ['id' => 1],
            ['nome' => 'admin']
        );

        Role::firstOrCreate(
            ['id' => 2],
            ['nome' => 'cidadao']
        );
        */

    }
}
