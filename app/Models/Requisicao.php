<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisicao extends Model
{
    use HasFactory;

    protected $table = 'requisicoes';

    protected $fillable = [
        'user_id',
        'livro_id',
        'numero',
        'data_requisicao',
        'data_prevista_entrega',
        'data_entrega_real',
        'foto_cidadao',
        'status',
    ];

    protected $casts = [
        'data_requisicao' => 'date',
        'data_prevista_entrega' => 'date',
        'data_entrega_real' => 'date',
    ];

    /** Relação com User (cidadão) */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Relação com Livro */
    public function livro()
    {
        return $this->belongsTo(Livro::class);
    }

    /** Escopo para requisições ativas */
    public function scopeAtivas($query)
    {
        return $query->where('status', 'ativo');
    }

    /** Gera o próximo número sequencial da requisição */
    public static function gerarNumeroSequencial(): string
    {
        $ultimo = self::orderBy('id', 'desc')->first();

        if (!$ultimo) {
            return 'REC-0001';
        }

        $numeroAtual = (int) str_replace('REC-', '', $ultimo->numero);
        $novo = $numeroAtual + 1;

        return 'REC-' . str_pad($novo, 4, '0', STR_PAD_LEFT);
    }

    public function review()
{
    return $this->hasOne(\App\Models\Review::class, 'requisicao_id');
}

}
