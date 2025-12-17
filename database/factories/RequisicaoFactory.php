<?php

namespace Database\Factories;

use App\Models\Requisicao;
use App\Models\User;
use App\Models\Livro;
use Illuminate\Database\Eloquent\Factories\Factory;

class RequisicaoFactory extends Factory
{
    protected $model = Requisicao::class;

    public function definition(): array
    {
        $dataRequisicao = now();

        return [
            'numero'                 => fake()->unique()->numberBetween(1, 999999),
            'user_id'                => User::factory(),
            'livro_id'               => Livro::factory(),
            'data_requisicao'        => $dataRequisicao,
            'data_prevista_entrega'  => $dataRequisicao->copy()->addDays(5),
            'status'                 => 'ativo',
        ];
    }

    public function entregue(): static
    {
        return $this->state(fn () => [
            'status' => 'entregue',
        ]);
    }
}
