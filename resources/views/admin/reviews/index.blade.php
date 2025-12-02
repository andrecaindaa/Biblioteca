@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1>Reviews pendentes / recentes</h1>

    @foreach($reviews as $review)
        <div class="card mb-3">
            <div class="card-body d-flex justify-content-between">
                <div>
                    <strong>{{ $review->livro->nome }}</strong>
                    <div>Cidadão: {{ $review->user->name }} ({{ $review->user->email }})</div>
                    <div>Rating: {{ $review->rating ?? '—' }}</div>
                    <div>Comentário: {{ Str::limit($review->comentario, 200) }}</div>
                </div>
                <div class="text-end">
                    <a href="{{ route('admin.reviews.show', $review->id) }}" class="btn btn-sm btn-primary">Ver / Moderar</a>
                </div>
            </div>
        </div>
    @endforeach

    <div class="mt-4">
        {{ $reviews->links() }}
    </div>
</div>
@endsection
