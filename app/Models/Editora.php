<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Editora extends Model
{
    use HasFactory;

    protected $table = 'editoras';
    protected $fillable = ['nome', 'logo_path'];

    public function livros()
    {
        return $this->hasMany(Livro::class, 'editora_id');
    }
}
