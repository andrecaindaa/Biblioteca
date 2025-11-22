<?php

namespace App\Mail;

use App\Models\Requisicao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequisicaoConfirmada extends Mailable
{
    use Queueable, SerializesModels;

    public Requisicao $requisicao;

    /**
     * Criar nova instância do Mailable
     */
    public function __construct(Requisicao $requisicao)
    {
        $this->requisicao = $requisicao;
    }

    /**
     * Construir o email
     */
    public function build()
    {
        return $this->subject("Confirmação de Requisição #{$this->requisicao->numero}")
                    ->markdown('emails.requisicao.confirmada')
                    ->with([
                        'requisicao' => $this->requisicao,
                        'livro' => $this->requisicao->livro,
                        'user' => $this->requisicao->user,
                    ]);
    }
}
