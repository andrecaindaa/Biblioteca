@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h1 class="mb-4">📦 Requisições</h1>

    <!-- Indicadores -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="p-3 bg-success text-white rounded shadow">
                <h3>{{ $ativas }}</h3>
                <p>Requisições Ativas</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-3 bg-primary text-white rounded shadow">
                <h3>{{ $ultimos30 }}</h3>
                <p>Últimos 30 dias</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-3 bg-warning text-dark rounded shadow">
                <h3>{{ $entreguesHoje }}</h3>
                <p>Entregues Hoje</p>
            </div>
        </div>
    </div>

    <!-- Tabela -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nº</th>
                <th>Livro</th>
                <th>Cidadão</th>
                <th>Data Req</th>
                <th>Prev. Entrega</th>
                <th>Real Entrega</th>
                <th>Status</th>
                <th>Ações</th>
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
                <td>{{ $r->data_entrega_real ? $r->data_entrega_real->format('d/m/Y') : '—' }}</td>
                <td>
                    @if($r->status === 'ativo')
                        <span class="badge bg-success">Ativo</span>
                    @else
                        <span class="badge bg-secondary">Entregue</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('requisicoes.show', $r->id) }}" class="btn btn-info btn-sm">Ver</a>

                    @if(auth()->user()->isAdmin() && $r->status === 'ativo')
                    <form action="{{ route('requisicoes.entregar', $r->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button class="btn btn-warning btn-sm">
                            Confirmar Entrega
                        </button>
                    </form>

                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
