<?php

namespace App\Mail;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PedidoPagoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pedido;

    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido;
    }

    public function build()
    {
        return $this->subject("Confirmação de Pagamento - Pedido #{$this->pedido->id}")
                    ->view('emails.pedido.pago');
    }
}
