<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'requisicao_id',
        'livro_id',
        'user_id',
        'rating',
        'comentario',
        'status',
        'justificacao_recusa',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function requisicao()
    {
        return $this->belongsTo(\App\Models\Requisicao::class, 'requisicao_id');
    }

    public function livro()
    {
        return $this->belongsTo(\App\Models\Livro::class);
    }

    public function isAtivo(): bool
    {
        return $this->status === 'ativo';
    }
}
