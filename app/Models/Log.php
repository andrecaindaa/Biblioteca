<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $fillable = [
        'user_id',
        'modulo',
        'acao',
        'objeto_id',
        'ip',
        'browser',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
