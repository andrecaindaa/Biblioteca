<?php

use App\Models\User;
use App\Models\Requisicao;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('mostra apenas as requisições do utilizador autenticado', function () {

    $userA = User::factory()->create(['role_id' => 2]);
    $userB = User::factory()->create(['role_id' => 2]);

    Requisicao::factory()->count(2)->create(['user_id' => $userA->id]);
    Requisicao::factory()->count(1)->create(['user_id' => $userB->id]);

    actingAs($userA)
        ->get(route('requisicoes.index'))
        ->assertOk()
        ->assertSeeText('Requisição');
});
