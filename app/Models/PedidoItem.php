<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PedidoItem extends Model
{
    use HasFactory;

    protected $table = 'pedido_items';

    protected $fillable = ['pedido_id','livro_id','quantidade','preco'];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function livro()
    {
        return $this->belongsTo(Livro::class, 'livro_id');
    }

    public function getSubTotalAttribute()
    {
        return $this->quantidade * ($this->preco ?? 0);
    }
}
