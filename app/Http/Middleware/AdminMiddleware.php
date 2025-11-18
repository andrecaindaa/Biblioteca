<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- Certifica-te que isto está presente

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Verifica se o utilizador está autenticado e se é admin
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado. Apenas administradores.');
        }

        return $next($request);
    }
}
