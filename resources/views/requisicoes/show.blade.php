@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h1 class="mb-4">📦 Detalhes da Requisição #{{ $requisicao->numero ?? '—' }}</h1>

    <div class="row">
        <!-- Livro -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-3">
                @if(!empty($requisicao->livro->capa))
                    <img src="{{ asset('storage/' . $requisicao->livro->capa) }}" class="card-img-top" alt="{{ $requisicao->livro->nome ?? 'Livro' }}">
                @endif

                <div class="card-body">
                    <h5 class="card-title">{{ $requisicao->livro->nome ?? '—' }}</h5>
                    <p class="text-muted">
                        Autor:
                        @if(!empty($requisicao->livro->autores) && $requisicao->livro->autores->count() > 0)
                            @foreach($requisicao->livro->autores as $autor)
                                {{ $autor->nome }}@if(!$loop->last), @endif
                            @endforeach
                        @else
                            — Sem autores registados
                        @endif
                    </p>

                    @if($requisicao->status === 'ativo')
                        <span class="badge bg-success">Ativo</span>
                    @else
                        <span class="badge bg-secondary">Entregue</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Dados da Requisição -->
        <div class="col-md-8">
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h4 class="card-title">📅 Informações da Requisição</h4>

                    <p><strong>Data da Requisição:</strong>
                        {{ $requisicao->data_requisicao?->format('d/m/Y') ?? '—' }}
                    </p>

                    <p><strong>Data Prevista de Entrega:</strong>
                        {{ $requisicao->data_prevista_entrega?->format('d/m/Y') ?? '—' }}
                    </p>

                    <p><strong>Data Real de Entrega:</strong>
                        {{ $requisicao->data_entrega_real?->format('d/m/Y') ?? '—' }}
                    </p>

                    <p><strong>Status:</strong>
                        @if($requisicao->status === 'ativo')
                            <span class="badge bg-success">Ativo</span>
                        @else
                            <span class="badge bg-secondary">Entregue</span>
                        @endif
                    </p>

                    @if($requisicao->status === 'ativo' && auth()->user()?->isAdmin())
                        <a href="{{ route('requisicoes.confirmar', $requisicao->id) }}" class="btn btn-warning">
                            Confirmar Entrega
                        </a>
                    @endif
                </div>
            </div>

            <!-- Dados do cidadão -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">👤 Cidadão</h4>

                    <p><strong>Nome:</strong> {{ $requisicao->user->name ?? '—' }}</p>
                    <p><strong>Email:</strong> {{ $requisicao->user->email ?? '—' }}</p>

                    @if(!empty($requisicao->foto_cidadao))
                        <p><strong>Foto tirada na requisição:</strong></p>
                        <img src="{{ asset('storage/' . $requisicao->foto_cidadao) }}" class="img-fluid rounded shadow" width="200" alt="Foto do cidadão">
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
