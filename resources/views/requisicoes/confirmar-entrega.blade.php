@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h1 class="mb-4">📦 Confirmar Entrega do Livro</h1>

    <div class="card shadow-sm">
        <div class="card-body">

            <h4>📘 Livro</h4>
            <p><strong>{{ $requisicao->livro->nome }}</strong></p>

            <hr>

            <h4>👤 Cidadão</h4>
            <p>{{ $requisicao->user->name }} ({{ $requisicao->user->email }})</p>

            <hr>

            <h4>📅 Datas</h4>
            <p><strong>Requisição:</strong> {{ $requisicao->data_requisicao->format('d/m/Y') }}</p>
            <p><strong>Prevista:</strong> {{ $requisicao->data_prevista_entrega->format('d/m/Y') }}</p>

            <form action="{{ route('requisicoes.confirmar.store', $requisicao->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Data Real de Entrega</label>
                    <input type="date" name="data_entrega_real" class="form-control" required
                           value="{{ now()->format('Y-m-d') }}">
                </div>

                <button type="submit" class="btn btn-success">Confirmar Entrega</button>
                <a href="{{ route('requisicoes.index') }}" class="btn btn-secondary">Cancelar</a>
            </form>

        </div>
    </div>

</div>
@endsection
