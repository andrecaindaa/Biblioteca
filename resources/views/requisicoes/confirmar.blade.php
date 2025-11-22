@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Confirmar Entrega: {{ $requisicao->livro->nome }}</h1>

<p>Requisição: <strong>{{ $requisicao->numero }}</strong></p>
<p>Cidadão: <strong>{{ $requisicao->user->name }}</strong></p>
<p>Data Requisição: <strong>{{ $requisicao->data_requisicao->format('d/m/Y') }}</strong></p>
<p>Data Prevista Entrega: <strong>{{ $requisicao->data_prevista_entrega->format('d/m/Y') }}</strong></p>

<form method="POST" action="{{ route('requisicoes.confirmar.store', $requisicao) }}">
    @csrf
    <button type="submit" class="btn btn-success mt-4">Confirmar Entrega</button>
</form>
@endsection
