@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Painel do Administrador</h1>

    <div class="grid grid-cols-3 gap-6">
        <div class="p-6 bg-blue-100 rounded shadow">
            <h2 class="text-lg font-semibold">Requisições Ativas</h2>
            <p class="text-3xl">{{ $requisicoesAtivas }}</p>
        </div>
        <div class="p-6 bg-green-100 rounded shadow">
            <h2 class="text-lg font-semibold">Requisições Últimos 30 Dias</h2>
            <p class="text-3xl">{{ $requisicoesUltimos30Dias }}</p>
        </div>
        <div class="p-6 bg-yellow-100 rounded shadow">
            <h2 class="text-lg font-semibold">Livros Entregues Hoje</h2>
            <p class="text-3xl">{{ $livrosEntreguesHoje }}</p>
        </div>
    </div>
</div>
@endsection
