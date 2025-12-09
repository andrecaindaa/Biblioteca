<?php

namespace App\Mail;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminPedidoNotificacaoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pedido;

    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido;
    }

    public function build()
    {
        return $this->subject("Novo Pedido Pago #{$this->pedido->id}")
                    ->view('emails.pedido.admin_notificacao');
    }
}
