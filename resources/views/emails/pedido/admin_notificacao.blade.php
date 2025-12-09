<p>Novo pedido pago: #{{ $pedido->id }}</p>
<p>Utilizador: {{ $pedido->user->name }} ({{ $pedido->user->email }})</p>
<p>Total: €{{ number_format($pedido->total,2) }}</p>
