<?php

use App\Models\User;
use App\Models\Livro;
use App\Models\Requisicao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('permite marcar um livro como entregue', function () {

    $admin   = User::factory()->create(['role_id' => 1]);
    $cidadao = User::factory()->create(['role_id' => 2]);
    $livro   = Livro::factory()->create();

    $requisicao = Requisicao::factory()->create([
        'user_id'  => $cidadao->id,
        'livro_id' => $livro->id,
        'status'   => 'ativo',
    ]);

    actingAs($admin)
        ->post(route('requisicoes.entregar', $requisicao))
        ->assertRedirect();

    expect($requisicao->fresh()->status)->toBe('entregue');
});
