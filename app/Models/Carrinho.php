<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Carrinho extends Model
{
    use HasFactory;

    protected $table = 'carrinhos';

    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CarrinhoItem::class, 'carrinho_id');
    }
}
