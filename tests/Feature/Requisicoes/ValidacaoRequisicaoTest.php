<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;
use App\Models\Requisicao;


uses(RefreshDatabase::class);

it('impede a criação de requisição sem livro válido', function () {

    $user = User::factory()->create(['role_id' => 2]);

    actingAs($user)
        ->post('/livros/999/requisitar')
        ->assertStatus(404);

        expect(Requisicao::count())->toBe(0);
});
