<?php

namespace App\Mail;

use App\Models\Requisicao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequisicaoCriada extends Mailable
{
    use Queueable, SerializesModels;

    public Requisicao $requisicao;

    /**
     * Create a new message instance.
     */
    public function __construct(Requisicao $requisicao)
    {
        $this->requisicao = $requisicao;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $mail = $this->subject('Nova Requisição de Livro')
            ->view('emails.requisicao-criada')
            ->with([
                'requisicao' => $this->requisicao,
                'livro' => $this->requisicao->livro,
                'user' => $this->requisicao->user,
            ]);

        // Anexar capa do livro se existir
        if ($this->requisicao->livro->imagem_capa) {
            $mail->attach(storage_path('app/public/' . $this->requisicao->livro->imagem_capa), [
                'as' => 'capa.jpg',
                'mime' => 'image/jpeg',
            ]);
        }

        return $mail;
    }
}
