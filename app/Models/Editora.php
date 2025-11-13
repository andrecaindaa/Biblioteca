<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Editora extends Model
{
    use HasFactory;

    protected $table = 'editoras';
    protected $fillable = ['nome', 'logo_path', 'notas'];



    protected function notas(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? decrypt($value) : null,
            set: fn ($value) => $value ? encrypt($value) : null,
        );
    }

    public function livros()
    {
        return $this->hasMany(Livro::class, 'editora_id');
    }
}
