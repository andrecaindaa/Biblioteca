<?php

namespace App\Mail;

use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewReviewForAdmin extends Mailable
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

        return $this->subject("Nova review pendente para: {$livro->nome}")
                    ->view('emails.admin.new_review')
                    ->with([
                        'review' => $this->review,
                        'livro' => $livro,
                        'user' => $user,
                        'link' => route('admin.reviews.show', $this->review->id),
                    ]);
    }
}
