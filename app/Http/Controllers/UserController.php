<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Livro;
use App\Models\Requisicao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\LogService;


class UserController extends Controller
{
    /**
     * Lista todos os utilizadores (apenas Admin).
     */
    public function index()
    {
        $users = User::with('role')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Mostra o perfil + histórico de requisições.
     */
    public function show(User $user)
    {
        $requisicoes = $user->requisicoes()->with('livro')->latest()->get();

        return view('admin.users.show', compact('user', 'requisicoes'));
    }

    /**
     * Formulário para criar um novo Admin.
     */
    public function create()
    {
        return view('admin.users.create');
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

        //User::create([
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => bcrypt($request->password),
            'role_id'  => 1, // ADMIN
        ]);

        LogService::registar(
    'Utilizadores',
    'Criou um utilizador',
    $user->id
);

       return redirect()->route('users.index')
            ->with('success', 'Administrador criado com sucesso.');
    }


    /* ============================================================
     |     ROTAS QUE FALTAVAM (edit, update, destroy)
     ============================================================ */

    /**
     * Formulário de edição de utilizador.
     */
    public function edit(User $user)
    {
        $roles = \App\Models\Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Atualiza um utilizador.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role_id' => $request->role_id,
        ]);

            //log
        LogService::registar(
    'Utilizadores',
    'Atualizou um utilizador',
    $user->id
);


        return redirect()->route('users.index')
            ->with('success', 'Utilizador atualizado com sucesso.');
    }

    /**
     * Apaga um utilizador.
     */
    public function destroy(User $user)
    {

        $userId = $user->id;
    $user->delete();

    LogService::registar(
        'Utilizadores',
        'Eliminou um utilizador',
        $userId
    );

        return redirect()->route('users.index')
            ->with('success', 'Utilizador eliminado com sucesso.');
    }


    /* ============================================================
     |           MÓDULO DE REQUISIÇÕES (CIDADÃO)
     ============================================================ */

    /**
     * Formulário para requisitar um livro.
     */
    public function requisitarForm(Livro $livro)
    {
        if (Auth::user()->isAdmin()) {
        return back()->with('error', 'Administradores não podem fazer requisições.');
                }

                if (!$livro->isDisponivel()) {
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


        if ($livro->stock <= 0) {

             LogService::registar(
        'Requisições',
        'Tentou requisitar livro sem stock',
        $livro->id
    );
    return back()->with('error', 'Livro sem stock disponível.');
    }


        if (! $livro->isDisponivel()) {
            return back()->with('error', 'Este livro já não está disponível.');
        }

        // Guardar foto se enviada
        $fotoPath = null;
        if ($request->hasFile('foto_cidadao')) {
            $fotoPath = $request->file('foto_cidadao')->store('requisicoes', 'public');
        }

        //Requisicao::create([
        $requisicao = Requisicao::create([
            'user_id' => Auth::id(),
            'livro_id' => $livro->id,
            'numero' => Requisicao::gerarNumeroSequencial(),
            'data_requisicao' => now()->toDateString(),
            'data_prevista_entrega' => $request->data_prevista_entrega,
            'foto_cidadao' => $fotoPath,
            'status' => 'ativo',
        ]);
        LogService::registar(
            'Requisições',
            'Criou uma requisição',
            $requisicao->id
        );

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

        LogService::registar(
    'Requisições',
    'Entregou um livro',
    $requisicao->id
);

        return back()->with('success', 'Livro entregue com sucesso.');
    }
}
