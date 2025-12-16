<?php

use App\Models\User;
use App\Models\Livro;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('impede requisitar livro sem stock disponível', function () {

    $user = User::factory()->create(['role_id' => 2]);

    $livro = Livro::factory()->create([
        'stock' => 0
    ]);

    actingAs($user)
        ->post(route('users.requisitar.store', $livro))
        ->assertSessionHas('error');
});
