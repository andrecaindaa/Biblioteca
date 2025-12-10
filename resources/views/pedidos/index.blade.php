@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-4xl p-6">
    <h1 class="text-2xl font-bold mb-4">Minhas Encomendas</h1>

    @if($pedidos->isEmpty())
        <p>Não existem encomendas.</p>
    @else
        <table class="table w-full">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Ver</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pedidos as $pedido)
                    <tr>
                        <td>{{ $pedido->id }}</td>
                        <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ number_format($pedido->total, 2, ',', '.') }} €</td>
                        <td>{{ ucfirst($pedido->status) }}</td>
                        <td>
                            <a href="{{ route('pedidos.show', $pedido->id) }}" class="btn btn-primary btn-sm">
                                Detalhes
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
