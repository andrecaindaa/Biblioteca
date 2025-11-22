@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Gestão de Requisições</h1>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Número</th>
                <th>Livro</th>
                <th>Utilizador</th>
                <th>Data Requisição</th>
                <th>Data Prevista</th>
                <th>Data Entrega</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requisicoes as $r)
            <tr>
                <td>{{ $r->numero }}</td>
                <td>{{ $r->livro->nome }}</td>
                <td>{{ $r->user->name }}</td>
                <td>{{ $r->data_requisicao->format('d/m/Y') }}</td>
                <td>{{ $r->data_prevista_entrega->format('d/m/Y') }}</td>
                <td>{{ $r->data_entrega_real ? $r->data_entrega_real->format('d/m/Y') : '-' }}</td>
                <td>
                    @if($r->status == 'ativo')
                        <span class="badge bg-warning">Ativo</span>
                    @else
                        <span class="badge bg-success">Concluído</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
