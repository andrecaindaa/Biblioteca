<?php

namespace App\Http\Controllers;

use App\Models\Carrinho;
use App\Models\CarrinhoItem;
use App\Models\Livro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarrinhoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin()) abort(403);

        $carrinho = Carrinho::firstOrCreate(['user_id' => $user->id]);
        $carrinho->load('items.livro');

        $total = $carrinho->items->sum(fn($i) => $i->sub_total ?? ($i->quantidade * ($i->livro->preco ?? 0)));

        return view('carrinho.index', compact('carrinho','total'));
    }

    public function adicionar(Request $request, Livro $livro)
    {
        $user = Auth::user();
        if ($user->isAdmin()) return back()->with('error', 'Administradores não podem adicionar ao carrinho.');

        $carrinho = Carrinho::firstOrCreate(['user_id' => $user->id]);

        $item = $carrinho->items()->where('livro_id', $livro->id)->first();

        if ($item) {
            $item->increment('quantidade', 1);
            $item->touch();
        } else {
            $carrinho->items()->create([
                'livro_id' => $livro->id,
                'quantidade' => 1,
            ]);
        }

        return redirect()
    ->route('carrinho.index')
    ->with('success', 'Livro adicionado ao carrinho.');

    }

    public function atualizar(Request $request, CarrinhoItem $item)
    {
        $request->validate([
    'quantidade' => ['required', 'integer', 'min:1'],
]);

        $user = Auth::user();
        if ($user->isAdmin()) abort(403);

        if ($item->carrinho->user_id !== $user->id) abort(403);

        $item->update(['quantidade' => $request->quantidade]);

        return back()->with('success', 'Quantidade atualizada.');
    }

    public function remover(CarrinhoItem $item)
    {
        $user = Auth::user();
        if ($user->isAdmin()) abort(403);
        if ($item->carrinho->user_id !== $user->id) abort(403);

        $item->delete();

        return back()->with('success', 'Item removido do carrinho.');
    }
}
