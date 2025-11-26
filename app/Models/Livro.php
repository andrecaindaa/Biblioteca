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

    /**
     * Mutator para garantir que o preço seja sempre um decimal válido
     */
    public function setPrecoAttribute($value)
    {
        if ($value === null || $value === '') {
            $this->attributes['preco'] = 0.00;
            return;
        }

        // Se for string, limpa e converte
        if (is_string($value)) {
            $cleanedValue = preg_replace('/[^\d,\.]/', '', $value);
            $cleanedValue = str_replace(',', '.', $cleanedValue);
            $value = floatval($cleanedValue);
        }

        $floatValue = max(0, floatval($value));
        $this->attributes['preco'] = round($floatValue, 2);
    }

    /**
     * Accessor para garantir que sempre retornamos um float
     */
    public function getPrecoAttribute($value)
    {
        if (is_string($value)) {
            $cleanedValue = preg_replace('/[^\d,\.]/', '', $value);
            $cleanedValue = str_replace(',', '.', $cleanedValue);
            return floatval($cleanedValue);
        }

        return $value ?? 0.00;
    }

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

    public function requisicoes()
    {
        return $this->hasMany(Requisicao::class);
    }

    public function requisicaoAtiva()
    {
        return $this->hasOne(Requisicao::class)->where('status', 'ativo');
    }

    public function isDisponivel(): bool
    {
        return $this->requisicaoAtiva()->doesntExist();
    }

    public function getCapaAttribute()
    {
        return $this->attributes['imagem_capa'] ?? null;
    }
}
