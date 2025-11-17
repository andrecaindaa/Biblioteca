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

    /**
 * Gera o próximo número sequencial da requisição
 */
public static function gerarNumeroSequencial(): string
{
    // Vai buscar a última requisição criada
    $ultimo = self::orderBy('id', 'desc')->first();

    if (!$ultimo) {
        return 'REC-0001';
    }

    // Extrair o número atual (REC-0007 → 7)
    $numeroAtual = (int) str_replace('REC-', '', $ultimo->numero);

    // Incrementar +1
    $novo = $numeroAtual + 1;

    // Formatado com 4 dígitos
    return 'REC-' . str_pad($novo, 4, '0', STR_PAD_LEFT);
}

}
