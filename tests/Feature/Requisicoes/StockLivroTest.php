<?php

use App\Models\User;
use App\Models\Livro;
use App\Models\Requisicao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('impede requisitar um livro sem stock disponível', function () {

    $user = User::factory()->create(['role_id' => 2]);

    $livroSemStock = Livro::factory()->create([
        'stock' => 0,
    ]);

    actingAs($user)
        ->post(route('users.requisitar.store', $livroSemStock), [
            'data_prevista_entrega' => now()->addDays(5)->toDateString(),
        ])
        ->assertRedirect()
        ->assertSessionHas('error');

    expect(Requisicao::count())->toBe(0);
});
