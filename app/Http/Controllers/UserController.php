<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Livro;
use App\Models\Requisicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Lista todos os utilizadores (apenas Admin).
     */
    public function index()
    {
        $users = User::with('role')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Mostra o perfil + histórico de requisições.
     */
    public function show(User $user)
    {
        $requisicoes = $user->requisicoes()->with('livro')->latest()->get();

        return view('users.show', compact('user', 'requisicoes'));
    }

    /**
     * Formulário para criar um novo Admin.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Guarda um novo Admin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role_id'  => 1, // ADMIN
        ]);

        return redirect()->route('users.index')
            ->with('success', 'Administrador criado com sucesso.');
    }



    /* ============================================================
     |              MÓDULO DE REQUISIÇÕES (CIDADÃO)
     ============================================================ */

    /**
     * Formulário para requisitar um livro.
     */
    public function requisitarForm(Livro $livro)
    {
        if (! $livro->isDisponivel()) {
            return back()->with('error', 'Este livro está atualmente requisitado.');
        }

        return view('users.requisitar', compact('livro'));
    }

    /**
     * Submete a requisição.
     */
    public function requisitar(Request $request, Livro $livro)
    {
        $request->validate([
            'data_prevista_entrega' => 'required|date|after:today',
            'foto_cidadao' => 'nullable|image|max:2048',
        ]);

        if (! $livro->isDisponivel()) {
            return back()->with('error', 'Este livro já não está disponível.');
        }

        // Guardar foto se enviada
        $fotoPath = null;
        if ($request->hasFile('foto_cidadao')) {
            $fotoPath = $request->file('foto_cidadao')->store('requisicoes', 'public');
        }

        Requisicao::create([
            'user_id' => Auth::id(),
            'livro_id' => $livro->id,
            'numero' => Requisicao::gerarNumeroSequencial(),
            'data_requisicao' => now()->toDateString(),
            'data_prevista_entrega' => $request->data_prevista_entrega,
            'foto_cidadao' => $fotoPath,
            'status' => 'ativo',
        ]);

        return redirect()->route('users.show', Auth::id())
            ->with('success', 'Requisição criada com sucesso.');
    }

    /**
     * Marcar que um livro foi entregue.
     */
    public function entregar(Requisicao $requisicao)
    {
        if ($requisicao->status === 'entregue') {
            return back()->with('info', 'Este livro já foi entregue.');
        }

        $requisicao->update([
            'data_entrega_real' => now()->toDateString(),
            'status' => 'entregue',
        ]);

        return back()->with('success', 'Livro entregue com sucesso.');
    }
}
