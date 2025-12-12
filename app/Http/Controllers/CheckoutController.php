<?php

namespace App\Http\Controllers;

use App\Models\Carrinho;
use App\Models\Pedido;
use App\Models\PedidoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function addressForm()
    {
        $user = Auth::user();
        if ($user->isAdmin()) abort(403);

        $carrinho = Carrinho::with('items.livro')->where('user_id', $user->id)->first();
        if (!$carrinho || $carrinho->items->isEmpty()) return redirect()->route('carrinho.index')->with('error','Carrinho vazio.');

        return view('checkout.address', compact('carrinho'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
    'morada' => 'required|string|max:1500',
]);


        session(['checkout_morada' => $request->morada]);

        return redirect()->route('checkout.payment');
    }

    public function payment()
{
    $user = Auth::user();
    if ($user->isAdmin()) abort(403);

    $carrinho = Carrinho::with('items.livro')->where('user_id', $user->id)->first();
    if (!$carrinho || $carrinho->items->isEmpty()) {
        return redirect()->route('carrinho.index')->with('error', 'Carrinho vazio.');
    }

    // Calcular total
    $total = 0;
    $lineItems = [];

    foreach ($carrinho->items as $item) {
        $preco = floatval($item->livro->preco ?? 0);
        $qty = intval($item->quantidade);
        $total += $preco * $qty;

        $lineItems[] = [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $item->livro->nome,
                    'images' => $item->livro->imagem_capa ? [asset($item->livro->imagem_capa)] : [],
                ],
                'unit_amount' => intval(round($preco * 100)),
            ],
            'quantity' => $qty,
        ];
    }

    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => $lineItems,
        'mode' => 'payment',
        'success_url' => route('checkout.success'),
        'cancel_url' => route('checkout.cancel'),
        'metadata' => [
            'user_id' => $user->id,
        ],
    ]);

    // Criar pedido pendente
    $pedido = Pedido::create([
        'user_id' => $user->id,
        'status' => 'pending',
        'morada_entrega' => session('checkout_morada'),
        'stripe_session_id' => $session->id,
        'total' => $total,
    ]);

    //  CRIAR ITEMS DO PEDIDO
    foreach ($carrinho->items as $item) {
        PedidoItem::create([
            'pedido_id' => $pedido->id,
            'livro_id' => $item->livro_id,
            'quantidade' => $item->quantidade,
            'preco' => $item->livro->preco ?? 0,
        ]);
    }

    //  LIMPAR CARRINHO DEPOIS DE CRIAR O PEDIDO
    $carrinho->items()->delete();

    return view('checkout.payment', compact('session', 'pedido'));
}


    public function success()
    {
        return view('checkout.success');
    }


    public function cancel()
    {
        return view('checkout.cancel');
    }
}
