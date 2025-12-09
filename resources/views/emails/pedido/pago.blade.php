<p>Olá {{ $pedido->user->name }},</p>
<p>O pagamento do seu pedido #{{ $pedido->id }} foi confirmado.</p>
<p>Total: €{{ number_format($pedido->total,2) }}</p>
<p>Morada de entrega: {{ $pedido->morada_entrega }}</p>
