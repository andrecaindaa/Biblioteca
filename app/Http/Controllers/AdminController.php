<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requisicao;

class AdminController extends Controller
{
    public function index()
    {
        $requisicoesAtivas = Requisicao::where('status', 'ativo')->count();
        $requisicoesUltimos30Dias = Requisicao::where('created_at', '>=', now()->subDays(30))->count();
        $livrosEntreguesHoje = Requisicao::whereDate('data_entrega_real', now())->count();

        return view('admin.dashboard', compact(
            'requisicoesAtivas',
            'requisicoesUltimos30Dias',
            'livrosEntreguesHoje'
        ));
    }
}
