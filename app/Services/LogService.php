<?php

namespace App\Services;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogService
{
    public static function registar(
        string $modulo,
        string $acao,
        ?int $objetoId = null
    ): void {
        Log::create([
            'user_id'  => Auth::id(),
            'modulo'   => $modulo,
            'acao'     => $acao,
            'objeto_id'=> $objetoId,
            'ip'       => request()->ip(),
            'browser'  => request()->userAgent(),
        ]);
    }
}
