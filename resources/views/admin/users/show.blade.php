@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Perfil do Utilizador: {{ $user->name }}</h1>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Informações Pessoais</h5>
            <p><strong>Nome:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Papel:</strong> {{ $user->role->nome ?? 'Cidadão' }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Histórico de Requisições</h5>

            @if($requisicoes->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Livro</th>
                                <th>Data Requisição</th>
                                <th>Data Prevista</th>
                                <th>Data Entrega</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requisicoes as $requisicao)
                            <tr>
                                <td>{{ $requisicao->livro->nome }}</td>
                                <td>{{ \Carbon\Carbon::parse($requisicao->data_requisicao)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($requisicao->data_prevista_entrega)->format('d/m/Y') }}</td>
                                <td>
                                    @if($requisicao->data_entrega_real)
                                        {{ \Carbon\Carbon::parse($requisicao->data_entrega_real)->format('d/m/Y') }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $requisicao->status === 'ativo' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $requisicao->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">Nenhuma requisição encontrada.</p>
            @endif
        </div>
    </div>

    <div class="mt-3">
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Voltar à Lista</a>
    </div>

</div>
@endsection
