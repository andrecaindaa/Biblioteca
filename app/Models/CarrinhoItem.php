<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CarrinhoItem extends Model
{
    use HasFactory;

    protected $table = 'carrinho_items';

    protected $fillable = ['carrinho_id','livro_id','quantidade'];

    public function carrinho()
    {
        return $this->belongsTo(Carrinho::class, 'carrinho_id');
    }

    public function livro()
    {
        return $this->belongsTo(Livro::class, 'livro_id');
    }

    public function getSubTotalAttribute()
    {
        return $this->quantidade * ($this->livro->preco ?? 0);
    }
}
