<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isAdmin()) abort(403);

        $pedidos = Pedido::where('user_id', $user->id)->with('items.livro')->latest()->get();

        return view('pedidos.index', compact('pedidos'));
    }

    public function show(Pedido $pedido)
    {
        $user = Auth::user();
        if ($user->isAdmin() || $pedido->user_id === $user->id) {
            $pedido->load('items.livro','user');
            return view('pedidos.show', compact('pedido'));
        }

        abort(403);
    }
}
