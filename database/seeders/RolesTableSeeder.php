<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['nome' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Cidadão', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
