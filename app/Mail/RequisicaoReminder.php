<?php

namespace App\Mail;

use App\Models\Requisicao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequisicaoReminder extends Mailable
{
    use Queueable, SerializesModels;

    public Requisicao $requisicao;

    public function __construct(Requisicao $requisicao)
    {
        $this->requisicao = $requisicao;
    }

    public function build()
    {
        return $this->subject('Reminder: Entrega de Livro Amanhã')
                    ->view('emails.requisicao-reminder')
                    ->with([
                        'requisicao' => $this->requisicao,
                        'livro' => $this->requisicao->livro,
                        'user' => $this->requisicao->user,
                    ]);
    }
}
