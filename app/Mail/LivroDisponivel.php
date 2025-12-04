<?php

namespace App\Mail;

use App\Models\Livro;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LivroDisponivel extends Mailable
{
    use Queueable, SerializesModels;

    public $livro;

    public function __construct(Livro $livro)
    {
        $this->livro = $livro;
    }

    public function build()
    {
        return $this->subject("📚 O livro '{$this->livro->nome}' está disponível!")
                    ->view('emails.user.livro_disponivel')
                    ->with([
                        'livro' => $this->livro,
                        'link'  => route('catalogo.show', $this->livro->id),
                    ]);
    }
}
