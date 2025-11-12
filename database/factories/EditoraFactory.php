<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Editora;

class EditoraFactory extends Factory
{
    protected $model = Editora::class;

    public function definition()
    {
        return [
            'nome' => $this->faker->company(),
            'logo_path' => null,
        ];
    }
}
