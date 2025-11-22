<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Livro;
use App\Models\Autor;
use App\Models\Editora;

class CatalogoController extends Controller
{
    public function index(Request $request)
    {
        $query = Livro::with('autores', 'editora');

        if ($request->autor) {
            $query->whereHas('autores', fn($q) => $q->where('id', $request->autor));
        }

        if ($request->editora) {
            $query->where('editora_id', $request->editora);
        }

        if ($request->disponibilidade) {
            $query->when($request->disponibilidade === 'disponivel', fn($q) => $q->whereDoesntHave('requisicaoAtiva'));
            $query->when($request->disponibilidade === 'indisponivel', fn($q) => $q->whereHas('requisicaoAtiva'));
        }

        $livros = $query->get();
        $autores = Autor::all();
        $editoras = Editora::all();

        return view('catalogo.index', compact('livros','autores','editoras'));
    }

    public function show(Livro $livro)
{
    // Carregar autores, editora e requisicoes com usuário
    $livro->load('autores', 'editora', 'requisicoes.user');

    // Histórico de todas as requisições do livro
    $historico = $livro->requisicoes()->orderBy('created_at', 'desc')->get();

    return view('catalogo.show', compact('livro', 'historico'));
}

}
