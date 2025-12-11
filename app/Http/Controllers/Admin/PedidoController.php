<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use Illuminate\Http\Request;

class PedidoController extends Controller
{
    /**
     * Lista todas as encomendas (pagas e pendentes)
     */
    public function index()
    {
        $pedidos = Pedido::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.pedidos.index', compact('pedidos'));
    }

    /**
     * Mostra o detalhe de uma encomenda
     */
    public function show(Pedido $pedido)
    {
        // garantir relações carregadas
        $pedido->load(['items.livro', 'user']);

        return view('admin.pedidos.show', compact('pedido'));
    }
}
