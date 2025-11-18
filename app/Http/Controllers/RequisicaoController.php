<?php

namespace App\Http\Controllers;

use App\Models\Livro;
use App\Models\Requisicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequisicaoController extends Controller
{
    /**
     * Criar nova requisição
     */
    public function store(Request $request, Livro $livro)
{
    $user = Auth::user();

    // 1) Verifica se o livro está disponível
    if (!$livro->isDisponivel()) {
        return back()->with('error', 'Este livro já está requisitado.');
    }

    // 2) Verifica limite de 3 requisições por utilizador
    $requisicoesAtivas = Requisicao::where('user_id', $user->id)
        ->where('status', 'ativo')
        ->count();

    if ($requisicoesAtivas >= 3) {
        return back()->with('error', 'Atingiste o limite de 3 requisições ativas.');
    }

    // 3) Criar a requisição
    $requisicao = Requisicao::create([
        'user_id' => $user->id,
        'livro_id' => $livro->id,
        'numero' => Requisicao::gerarNumeroSequencial(),
        'data_requisicao' => today(),
        'data_prevista_entrega' => today()->addDays(5),
        'status' => 'ativo',
    ]);

    return back()->with('success', "Requisição criada com sucesso! Nº {$requisicao->numero}");
}


    /**
     * Marcar como entregue (Admin)
     */
    public function marcarEntregue(Requisicao $requisicao)
    {
        $requisicao->update([
            'status' => 'entregue',
            'data_entrega_real' => today(),
        ]);

        return back()->with('success', 'Livro marcado como entregue.');
    }
}
