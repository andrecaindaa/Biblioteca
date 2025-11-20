@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h1>{{ $livro->nome }}</h1>

    <div class="row mt-4">
        <div class="col-md-4">
            @if($livro->capa)
                <img src="{{ asset('storage/' . $livro->capa) }}" class="img-fluid rounded shadow">
            @endif
        </div>

        <div class="col-md-8">
            <p><strong>Autor:</strong> {{ $livro->autor->nome }}</p>
            <p><strong>Editora:</strong> {{ $livro->editora->nome }}</p>

            <p><strong>Status:</strong>
                @if($livro->isDisponivel())
                    <span class="badge bg-success">Disponível</span>
                @else
                    <span class="badge bg-danger">Requisitado</span>
                @endif
            </p>

            @if($livro->isDisponivel())
                <a href="{{ route('users.requisitar.view', $livro->id) }}" class="btn btn-success">
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
@endsection
