@extends('layouts.app')

@section('content')
<div class="container py-5">

    <h1 class="mb-4">📦 Encomendas</h1>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>#ID</th>
                <th>Utilizador</th>
                <th>Status</th>
                <th>Total</th>
                <th>Data</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pedidos as $pedido)
            <tr>
                <td>{{ $pedido->id }}</td>
                <td>{{ $pedido->user->name }}</td>
                <td>
                    @if ($pedido->status === 'paid')
                        <span class="badge bg-success">Pago</span>
                    @else
                        <span class="badge bg-warning text-dark">Pendente</span>
                    @endif
                </td>
                <td>{{ number_format($pedido->total, 2, ',', '.') }} €</td>
                <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('admin.pedidos.show', $pedido->id) }}" class="btn btn-sm btn-primary">
                        Ver detalhe
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $pedidos->links() }}

</div>
@endsection
