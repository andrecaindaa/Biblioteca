<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Mail\ReviewStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReviewController extends Controller
{


    public function index()
    {
        $reviews = Review::where('status', 'suspenso')
    ->with(['livro', 'user'])
    ->orderByDesc('created_at')
    ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        return view('admin.reviews.show', compact('review'));
    }

    public function approve(Review $review)
    {
        $review->status = 'ativo';
        $review->justificacao_recusa = null;
        $review->save();

        // notificar autor
        Mail::to($review->user->email)->send(new ReviewStatusChanged($review));

        return redirect()->route('admin.reviews.index')->with('success', 'Review aprovada.');
    }

    public function reject(Request $request, Review $review)
    {
        $data = $request->validate([
            'justificacao_recusa' => 'nullable|string|max:2000'
        ]);

        $review->status = 'recusado';
        $review->justificacao_recusa = $data['justificacao_recusa'] ?? null;
        $review->save();

        // notificar autor
        Mail::to($review->user->email)->send(new ReviewStatusChanged($review));

       return redirect()->route('admin.reviews.show', $review->id)
    ->with('success', 'Review recusada e autor notificado.');

    }
}
