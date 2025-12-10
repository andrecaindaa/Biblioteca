@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-4xl p-6">

    <h1 class="text-2xl font-bold mb-4">Detalhes da Encomenda #{{ $pedido->id }}</h1>

    <div class="card bg-base-200 p-4 mb-6">
        <p><strong>Status:</strong> {{ ucfirst($pedido->status) }}</p>
        <p><strong>Data:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Morada de Entrega:</strong> {{ $pedido->morada_entrega }}</p>
        <p><strong>Total:</strong> {{ number_format($pedido->total, 2, ',', '.') }} €</p>
    </div>

    <h2 class="text-xl font-semibold mb-3">Itens da Encomenda</h2>

    <table class="table w-full">
        <thead>
            <tr>
                <th>Livro</th>
                <th>Preço</th>
                <th>Qtd</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pedido->items ?? [] as $item)
                <tr>
                    <td>{{ $item->livro->nome }}</td>
                    <td>{{ number_format($item->preco, 2, ',', '.') }} €</td>
                    <td>{{ $item->quantidade }}</td>
                    <td>{{ number_format($item->subtotal, 2, ',', '.') }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-6">
        <a href="{{ route('pedidos.index') }}" class="btn btn-primary">
            ← Voltar às Encomendas
        </a>
    </div>

</div>
@endsection
