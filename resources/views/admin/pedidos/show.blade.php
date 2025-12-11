@extends('layouts.app')

@section('content')
<div class="container py-5">

    <h1 class="mb-4">📄 Detalhe da Encomenda #{{ $pedido->id }}</h1>

    <div class="mb-3">
        <strong>Cliente:</strong> {{ $pedido->user->name }} <br>
        <strong>Email:</strong> {{ $pedido->user->email }} <br>

        <strong>Estado:</strong>
        @if ($pedido->status === 'paid')
            <span class="badge bg-success">Pago</span>
        @else
            <span class="badge bg-warning text-dark">Pendente</span>
        @endif
        <br>

        <strong>Morada:</strong> {{ $pedido->morada_entrega }} <br>
        <strong>Data:</strong> {{ $pedido->created_at->format('d/m/Y H:i') }}
    </div>

    <h4 class="mt-4">📚 Livros Encomendados</h4>

    <table class="table">
        <thead>
            <tr>
                <th>Livro</th>
                <th>Quantidade</th>
                <th>Preço</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($pedido->items as $item)
                <tr>
                    {{-- Nome do livro --}}
                    <td>
                        @if($item->livro)
                            {{ $item->livro->nome }}
                        @else
                            <span class="text-danger">⚠ Livro não encontrado</span>
                        @endif
                    </td>

                    {{-- Quantidade --}}
                    <td>{{ $item->quantidade }}</td>

                    {{-- Preço --}}
                    <td>{{ number_format($item->preco ?? 0, 2, ',', '.') }} €</td>

                    {{-- Subtotal --}}
                    <td>{{ number_format(($item->preco ?? 0) * $item->quantidade, 2, ',', '.') }} €</td>
                </tr>
            @endforeach

        </tbody>
    </table>

    <div class="text-end">
        <h3>Total: {{ number_format($pedido->total, 2, ',', '.') }} €</h3>
    </div>

    <a href="{{ route('admin.pedidos.index') }}" class="btn btn-secondary mt-4">Voltar</a>

</div>
@endsection
