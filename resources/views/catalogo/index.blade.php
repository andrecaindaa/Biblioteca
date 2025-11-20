@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h1 class="mb-4">📚 Catálogo de Livros</h1>

    <div class="row">
        @foreach($livros as $livro)
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">

                @if($livro->capa)
                <img src="{{ asset('storage/' . $livro->capa) }}" class="card-img-top" height="250" style="object-fit: cover;">
                @endif

                <div class="card-body">
                    <h5 class="card-title">{{ $livro->nome }}</h5>
                    <p class="card-text text-muted">Autor: {{ $livro->autor->nome }}</p>

                    @if($livro->isDisponivel())
                        <span class="badge bg-success">Disponível</span>
                    @else
                        <span class="badge bg-danger">Requisitado</span>
                    @endif

                    <div class="mt-3 d-flex justify-content-between">
                        <a href="{{ route('catalogo.show', $livro->id) }}" class="btn btn-primary">
                            Ver Detalhes
                        </a>

                        @if($livro->isDisponivel())
                        <a href="{{ route('users.requisitar.view', $livro->id) }}" class="btn btn-success">
                            Requisitar
                        </a>
                        @endif

                    </div>

                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection
