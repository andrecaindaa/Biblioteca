<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livro extends Model
{
    use HasFactory;

    protected $table = 'livros';

    protected $fillable = [
        'isbn', 'nome', 'editora_id', 'bibliografia', 'imagem_capa', 'preco'
    ];

    protected $casts = [
        'preco' => 'decimal:2',
    ];

    // cifrar bibliografia no banco
    public function setBibliografiaAttribute($value)
    {
        $this->attributes['bibliografia'] = $value ? encrypt($value) : null;
    }

    public function getBibliografiaAttribute($value)
    {
        return $value ? decrypt($value) : null;
    }

    public function autores()
    {
        return $this->belongsToMany(Autor::class, 'autor_livro', 'livro_id', 'autor_id');
    }

    public function editora()
    {
        return $this->belongsTo(Editora::class, 'editora_id');
    }
}
