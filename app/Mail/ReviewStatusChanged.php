<?php

namespace App\Mail;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReviewStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public $review;

    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    public function build()
    {
        $livro = $this->review->livro;
        $user = $this->review->user;

        $subject = $this->review->status === 'ativo'
            ? "A sua avaliação para '{$livro->nome}' foi aprovada"
            : "A sua avaliação para '{$livro->nome}' foi recusada";

        return $this->subject($subject)
                    ->view('emails.user.review_status_changed')
                    ->with([
                        'review' => $this->review,
                        'livro' => $livro,
                        'user' => $user,
                    ]);
    }
}
