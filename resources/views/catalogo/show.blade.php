@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h1>{{ $livro->nome }}</h1>

    <div class="row mt-4">

        <div class="col-md-4">
            @if($livro->capa)
                <img src="{{ asset('storage/'.$livro->capa) }}"
                     class="book-cover-large shadow"
                     alt="Capa do livro {{ $livro->nome }}">
            @else
                <div class="book-cover-large bg-light border rounded d-flex justify-content-center align-items-center">
                    <span class="text-muted">Sem capa disponível</span>
                </div>
            @endif
        </div>

        <div class="col-md-8">

            <p><strong>Autor(es):</strong> {{ $livro->autores->pluck('nome')->join(', ') }}</p>
            <p><strong>Editora:</strong> {{ $livro->editora->nome }}</p>

            <p><strong>Status:</strong>
                @if($livro->isDisponivel())
                    <span class="badge bg-success">Disponível</span>
                @else
                    <span class="badge bg-danger">Requisitado</span>
                @endif
            </p>

            @if($livro->isDisponivel() && auth()->check() && !auth()->user()->isAdmin())
                <a href="{{ route('users.requisitar.form', $livro->id) }}" class="btn btn-success mt-2">
                    Requisitar Livro
                </a>
            @endif

        </div>
    </div>

    <hr>

    <h3 class="mt-4">📖 Histórico de Requisições</h3>

    @if($historico->isEmpty())
        <p>Este livro nunca foi requisitado.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nº</th>
                    <th>Cidadão</th>
                    <th>Data Requisição</th>
                    <th>Data Devolução</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historico as $r)
                <tr>
                    <td>{{ $r->numero }}</td>
                    <td>{{ $r->user->name }}</td>
                    <td>{{ $r->data_requisicao->format('d/m/Y') }}</td>
                    <td>{{ $r->data_entrega_real ? $r->data_entrega_real->format('d/m/Y') : '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>

<style>
.book-cover-large {
    width: 100%;
    aspect-ratio: 2 / 3;      /* Mesmo tamanho que catálogo */
    object-fit: cover;
    border-radius: 8px;
}
</style>

@endsection
