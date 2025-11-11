<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Livro extends Model
{
  public function autores()
{
    return $this->belongsToMany(Autor::class);
}

public function editora()
{
    return $this->belongsTo(Editora::class);
}

}

