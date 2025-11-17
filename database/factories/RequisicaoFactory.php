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
        $user = User::inRandomOrder()->first();
        $livro = Livro::inRandomOrder()->first();

        $data_inicio = $this->faker->dateTimeBetween('-1 month', 'now');
        $data_fim_prevista = (clone $data_inicio)->modify('+5 days');

        return [
            'numero' => Requisicao::max('numero') + 1 ?? 1,
            'user_id' => $user->id,
            'livro_id' => $livro->id,
            'data_inicio' => $data_inicio,
            'data_fim_prevista' => $data_fim_prevista,
            'status' => 'ativo'
        ];
    }
}
