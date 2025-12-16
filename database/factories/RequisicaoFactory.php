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
      //  $user = User::inRandomOrder()->first();
       // $livro = Livro::inRandomOrder()->first();

     //  $data_inicio = $this->faker->dateTimeBetween('-1 month', 'now');
   //    $data_fim_prevista = (clone $data_inicio)->modify('+5 days');

        return [
           // 'numero' => Requisicao::max('numero') + 1 ?? 1,
           // 'user_id' => $user->id,
            //'livro_id' => $livro->id,
             'numero' => fake()->unique()->numberBetween(1, 999999),
             'user_id' => User::factory(),   // seguro para testes
            'livro_id' => Livro::factory(), // seguro para testes

         //   'data_inicio' => $data_inicio,
            //'data_fim_prevista' => $data_fim_prevista,
            // 'data_inicio' => today(),
            //'data_fim_prevista' => today()->addDays(5),

            'status' => 'ativo'
        ];
    }

    /**
     * Estado: entregue
     */
    public function entregue(): static
    {
        return $this->state(fn () => [
            'status' => 'entregue',
            'data_entrega_real' => now(),
        ]);
    }
}
