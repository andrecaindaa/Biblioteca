<?php

namespace App\Http\Controllers;

use App\Models\Requisicao;
use App\Models\Livro;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\NovaRequisicaoMail;
use App\Mail\ConfirmacaoEntregaMail;

class RequisicaoController extends Controller
{
    /**
     * Criar nova requisição
     */
    public function store(Request $request, Livro $livro)
    {
        $user = Auth::user();

        // Livro disponível?
        if (!$livro->isDisponivel()) {
            return back()->with('error', 'Este livro já está requisitado.');
        }

        // Limite de 3 livros simultâneos
        $requisicoesAtivas = Requisicao::where('user_id', $user->id)
            ->where('status', 'ativo')
            ->count();

        if ($requisicoesAtivas >= 3) {
            return back()->with('error', 'Você atingiu o limite de 3 livros requisitados.');
        }

        // Criar requisição
        $requisicao = Requisicao::create([
            'user_id' => $user->id,
            'livro_id' => $livro->id,
            'numero' => Requisicao::gerarNumeroSequencial(),
            'data_requisicao' => today(),
            'data_prevista_entrega' => today()->addDays(5),
            'status' => 'ativo',
        ]);

        // Enviar email para usuário e Admins
        Mail::to($user->email)->send(new NovaRequisicaoMail($requisicao));

        $admins = User::where('role', 'admin')->get(); // supondo coluna 'role'
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new NovaRequisicaoMail($requisicao));
        }

        return back()->with('success', "Requisição criada com sucesso! Nº {$requisicao->numero}");
    }

    /**
     * Marcar entrega (Admin)
     */
    public function marcarEntregue(Requisicao $requisicao)
    {
        $requisicao->update([
            'status' => 'entregue',
            'data_entrega_real' => today(),
        ]);

        // Enviar email de confirmação
        $admins = User::where('role', 'admin')->get();
        Mail::to($requisicao->user->email)->send(new ConfirmacaoEntregaMail($requisicao));
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new ConfirmacaoEntregaMail($requisicao));
        }

        return back()->with('success', 'Livro marcado como entregue.');
    }

    /**
     * Listagem de requisições
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $requisicoes = Requisicao::with(['livro','user'])->latest()->get();
        } else {
            $requisicoes = Requisicao::with('livro')->where('user_id', $user->id)->latest()->get();
        }

        $ativas = Requisicao::where('status','ativo')->count();
        $ultimos30 = Requisicao::where('data_requisicao','>=', now()->subDays(30))->count();
        $entreguesHoje = Requisicao::where('data_entrega_real', today())->count();

        return view('requisicoes.index', compact('requisicoes','ativas','ultimos30','entreguesHoje'));
    }

    /**
     * Detalhe de uma requisição
     */
    public function show(Requisicao $requisicao)
    {
        $user = Auth::user();

        if (!$user->isAdmin() && $requisicao->user_id !== $user->id) {
            abort(403, 'Não autorizado.');
        }

        $requisicao->load(['livro.autores', 'user']);

        return view('requisicoes.show', compact('requisicao'));
    }
}
