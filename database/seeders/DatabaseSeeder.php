<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Editora;
use App\Models\Autor;
use App\Models\Livro;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $editoras = Editora::factory()->count(3)->create();
        $autores = Autor::factory()->count(6)->create();

        foreach ($editoras as $editora) {
            Livro::factory()->count(5)->create([
                'editora_id' => $editora->id,
            ])->each(function($livro) use ($autores){
                $livro->autores()->attach($autores->random(rand(1,3))->pluck('id')->toArray());
            });
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
}
