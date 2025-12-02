@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1>Deixar avaliação — {{ $livro->nome }}</h1>

    <form action="{{ route('reviews.store', $requisicao->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Avaliação (1-5)</label>
            <select name="rating" class="form-select">
                <option value="">Sem avaliação</option>
                @for($i=1;$i<=5;$i++)
                    <option value="{{ $i }}">{{ $i }} estrela{{ $i>1?'s':'' }}</option>
                @endfor
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Comentário</label>
            <textarea name="comentario" class="form-control" rows="6" maxlength="3000"></textarea>
        </div>

        <button class="btn btn-primary">Submeter avaliação (será moderada)</button>
        <a href="{{ route('requisicoes.show', $requisicao->id) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
