<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\PedidoPagoMail;
use App\Mail\AdminPedidoNotificacaoMail;
use App\Models\Carrinho;
use App\Models\CarrinhoItem;

class StripeWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;

            $pedido = Pedido::where('stripe_session_id', $session->id)->first();
            if (!$pedido) {
                // pode ter sido criado manualmente: tentar encontrar por metadata
                $pedido = Pedido::where('user_id', $session->metadata->user_id ?? null)
                              ->where('stripe_session_id', $session->id)
                              ->first();
            }

            if ($pedido && $pedido->status !== 'paid') {
                // marcar pago
                $pedido->update(['status' => 'paid']);

                // popular pedido_items a partir do carrinho antigo (se existir)
                $carrinho = Carrinho::where('user_id', $pedido->user_id)->with('items.livro')->first();

                if ($carrinho) {
                    foreach ($carrinho->items as $item) {
                        PedidoItem::create([
                            'pedido_id' => $pedido->id,
                            'livro_id' => $item->livro_id,
                            'quantidade' => $item->quantidade,
                            'preco' => $item->livro->preco ?? 0,
                        ]);
                    }

                    // limpar carrinho
                    $carrinho->items()->delete();
                }

                // enviar emails
                Mail::to($pedido->user->email)->send(new PedidoPagoMail($pedido));
                usleep(500000);

                // notificar admins
                foreach (\App\Models\User::where('role_id',1)->get() as $admin) {
                    Mail::to($admin->email)->send(new AdminPedidoNotificacaoMail($pedido));
                    usleep(500000);
                }
            }
        }

        return response('Webhook handled', 200);
    }
}
