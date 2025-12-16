<?php

use App\Models\User;
use App\Models\Livro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;


uses(RefreshDatabase::class);

it('permite que um utilizador crie uma requisição de um livro', function () {

    $user = User::factory()->create(['role_id' => 2]); // cidadão
    $livro = Livro::factory()->create();

    $this->actingAs($user)
        ->post(route('users.requisitar.store', $livro), [
            'data_prevista_entrega' => now()->addDays(5)->toDateString(),
        ])
        ->assertRedirect();

    $this->assertDatabaseHas('requisicoes', [
        'user_id'  => $user->id,
        'livro_id' => $livro->id,
        'status'   => 'ativo',
    ]);
});
