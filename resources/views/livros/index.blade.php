@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Lista de Livros</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Autor</th>
                <th>Ano</th>
                <th>Disponibilidade</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($livros as $livro)
            <tr>
                <td>{{ $livro->nome }}</td>
                <td>{{ $livro->autor }}</td>
                <td>{{ $livro->ano }}</td>
                <td>
                    @if($livro->disponivel)
                        <span class="badge bg-success">Disponível</span>
                    @else
                        <span class="badge bg-danger">Indisponível</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('livros.show', $livro->id) }}" class="btn btn-primary btn-sm">
                        Ver
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
