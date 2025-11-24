@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $livro->nome }}</h1>
    <p><strong>Autor:</strong> {{ $livro->autor }}</p>
    <p><strong>Ano:</strong> {{ $livro->ano }}</p>
    <p><strong>Género:</strong> {{ $livro->genero }}</p>

    <p>
        <strong>Status:</strong>
        @if($livro->disponivel)
            <span class="badge bg-success">Disponível</span>
        @else
            <span class="badge bg-danger">Indisponível</span>
        @endif
    </p>

    @if($livro->disponivel)
        <a href="{{ route('users.requisitar.form', $livro->id) }}" class="btn btn-success">Requisitar</a>
    @endif

    <a href="{{ route('admin.livros.index') }}" class="btn btn-secondary">Voltar</a>
</div>
@endsection
