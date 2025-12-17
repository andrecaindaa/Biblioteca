<?php

use App\Models\User;
use App\Models\Requisicao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

it('mostra apenas as requisições do utilizador autenticado', function () {

    $userA = User::factory()->create(['role_id' => 2]);
    $userB = User::factory()->create(['role_id' => 2]);

    $reqUserA1 = Requisicao::factory()->create([
        'user_id' => $userA->id,
        'numero'  => 1111,
    ]);

    $reqUserA2 = Requisicao::factory()->create([
        'user_id' => $userA->id,
        'numero'  => 2222,
    ]);

    $reqUserB = Requisicao::factory()->create([
        'user_id' => $userB->id,
        'numero'  => 9999,
    ]);

    actingAs($userA)
        ->get(route('requisicoes.index'))
        ->assertOk()
        ->assertSeeText('1111')
        ->assertSeeText('2222')
        ->assertDontSeeText('9999');
});
