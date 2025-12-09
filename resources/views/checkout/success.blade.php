@extends('layouts.app')
@section('content')
<div class="container mx-auto p-4">
  <div class="card p-6">
    <h2 class="text-2xl">Pagamento concluído!</h2>
    <p class="mt-2">Obrigado pela sua encomenda. Receberá um email com a confirmação.</p>
    <a href="{{ route('pedidos.index') }}" class="btn btn-primary mt-4">Ver encomendas</a>
  </div>
</div>
@endsection
