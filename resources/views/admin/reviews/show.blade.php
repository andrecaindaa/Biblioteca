@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1>Review para: {{ $review->livro->nome }}</h1>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Cidadão:</strong> {{ $review->user->name }} ({{ $review->user->email }})</p>
            <p><strong>Rating:</strong> {{ $review->rating ?? '—' }}</p>
            <p><strong>Comentário:</strong></p>
            <p>{{ $review->comentario }}</p>
            <p><strong>Status:</strong> {{ $review->status }}</p>
            @if($review->justificacao_recusa)
                <p><strong>Justificação recusa:</strong> {{ $review->justificacao_recusa }}</p>
            @endif
        </div>
    </div>

    <form action="{{ route('admin.reviews.approve', $review->id) }}" method="POST" class="d-inline">
        @csrf
        <button class="btn btn-success">Aprovar</button>
    </form>

    <button class="btn btn-danger" id="btn-recusar">Recusar</button>

    <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">Voltar</a>

    <!-- Modal simples para justificar recusa -->
    <div id="modal-recusar" style="display:none;" class="mt-3">
        <form action="{{ route('admin.reviews.reject', $review->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Justificação (opcional)</label>
                <textarea name="justificacao_recusa" class="form-control" rows="4"></textarea>
            </div>
            <button class="btn btn-danger">Confirmar recusa</button>
            <button type="button" class="btn btn-secondary" id="btn-fechar">Cancelar</button>
        </form>
    </div>
</div>

<script>
document.getElementById('btn-recusar').addEventListener('click', function(){
    document.getElementById('modal-recusar').style.display = 'block';
});
document.getElementById('btn-fechar')?.addEventListener('click', function(){
    document.getElementById('modal-recusar').style.display = 'none';
});
</script>
@endsection
