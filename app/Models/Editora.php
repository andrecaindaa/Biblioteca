<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Editora extends Model
{
    public function livros()
{
    return $this->hasMany(Livro::class);
}

}
