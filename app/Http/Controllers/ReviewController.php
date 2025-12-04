<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Requisicao;
use App\Mail\NewReviewForAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function create(Requisicao $requisicao)
    {
        $user = Auth::user();

        // Validar que a requisição pertence ao user
        if ($requisicao->user_id !== $user->id) abort(403);

        // Só permitir se livro entregue (data_entrega_real not null)
        if (!$requisicao->data_entrega_real) {
            return redirect()->back()->with('error', 'Só é possível avaliar após a entrega do livro.');
        }

        // Verificar se já existe review
        if ($requisicao->review()->exists()) {
            return redirect()->back()->with('error', 'Já existe uma avaliação para esta requisição.');
        }

        return view('reviews.create', ['requisicao' => $requisicao, 'livro' => $requisicao->livro]);
    }

    public function store(Request $request, Requisicao $requisicao)
    {
        $user = Auth::user();

        if ($requisicao->user_id !== $user->id) abort(403);
        if (!$requisicao->data_entrega_real) {
            return redirect()->back()->with('error', 'Só é possível avaliar após a entrega do livro.');
        }
        if ($requisicao->review()->exists()) {
            return redirect()->back()->with('error', 'Já existe uma avaliação para esta requisição.');
        }

        $data = $request->validate([
            'rating' => 'nullable|integer|min:1|max:5',
            'comentario' => 'nullable|string|max:3000',
        ]);

        $review = Review::create([
            'requisicao_id' => $requisicao->id,
            'livro_id' => $requisicao->livro_id,
            'user_id' => $user->id,
            'rating' => $data['rating'] ?? null,
            'comentario' => $data['comentario'] ?? null,
            'status' => 'suspenso',
        ]);

        // Notificar Admins por email
       $admins = \App\Models\User::where('role', 'admin')->pluck('email');

        $admins->each(function($email) use ($review){
            Mail::to($email)->queue(new NewReviewForAdmin($review));
        });


        return redirect()->route('requisicoes.show', $requisicao->id)
            ->with('success', 'Review submetida com sucesso e aguarda moderação do Admin.');
    }
}
