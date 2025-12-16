<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Livro;
use App\Models\Editora;

class LivroFactory extends Factory
{
    protected $model = Livro::class;

    public function definition()
    {
        return [
            'isbn' => $this->faker->unique()->isbn13(),
            'nome' => $this->faker->sentence(3),
            'editora_id' => Editora::factory(),
            'bibliografia' => $this->faker->paragraph(),
            'imagem_capa' => null,
            'preco' => $this->faker->randomFloat(2, 5, 100),
            'stock' => $this->faker->numberBetween(1, 10),
        ];
    }

     /**
     * Estado sem stock (para testes)
     */
    public function semStock(): static
    {
        return $this->state(fn () => [
            'stock' => 0,
        ]);
    }
}
