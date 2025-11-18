@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Perfil de {{ $user->name }}</h1>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Função:</strong> {{ $user->role->name ?? 'Cidadão' }}</p>

    <hr>

    <h3>Requisições</h3>
    @if($requisicoes->isEmpty())
        <p>Sem requisições até ao momento.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Número</th>
                    <th>Livro</th>
                    <th>Data Requisição</th>
                    <th>Data Prevista Entrega</th>
                    <th>Data Entrega Real</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requisicoes as $r)
                    <tr>
                        <td>{{ $r->numero }}</td>
                        <td>{{ $r->livro->nome }}</td>
                        <td>{{ $r->data_requisicao->format('d/m/Y') }}</td>
                        <td>{{ $r->data_prevista_entrega->format('d/m/Y') }}</td>
                        <td>{{ $r->data_entrega_real ? $r->data_entrega_real->format('d/m/Y') : '-' }}</td>
                        <td>
                            @if($r->status === 'ativo')
                                <span class="badge bg-success">Ativo</span>
                            @else
                                <span class="badge bg-secondary">Entregue</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

</div>
@endsection
