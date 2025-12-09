<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pedido extends Model
{
    use HasFactory;

    protected $table = 'pedidos';

    protected $fillable = [
        'user_id','status','morada_entrega','stripe_session_id','total'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(PedidoItem::class, 'pedido_id');
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
