<?php

namespace App\Mail;

use App\Models\Carrinho;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AbandonoCarrinhoMail extends Mailable
{
    use Queueable, SerializesModels;

    public $carrinho;

    public function __construct(Carrinho $carrinho)
    {
        $this->carrinho = $carrinho;
    }

    public function build()
    {
        return $this->subject('Precisando de ajuda com o seu carrinho?')
                    ->view('emails.carrinho.abandonado');
    }
}

