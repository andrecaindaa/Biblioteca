@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
  <h1 class="text-2xl mb-4">Carrinho</h1>

  @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error')) <div class="alert alert-error">{{ session('error') }}</div> @endif

  @if($carrinho->items->isEmpty())
    <div class="card p-4">O seu carrinho está vazio.</div>
  @else
    <div class="overflow-x-auto">
      <table class="table w-full">
        <thead><tr><th>Livro</th><th>Preço</th><th>Quantidade</th><th>Subtotal</th><th></th></tr></thead>
        <tbody>
          @foreach($carrinho->items as $item)
            <tr>
              <td class="flex items-center gap-3">
                @if($item->livro->imagem_capa)
                  <img src="{{ asset($item->livro->imagem_capa) }}" alt="" class="w-12 h-16 object-cover">
                @endif
                <div>
                  <div class="font-medium">{{ $item->livro->nome }}</div>
                  <div class="text-sm text-gray-500">{{ $item->livro->autores->pluck('nome')->join(', ') }}</div>
                </div>
              </td>
              <td>€ {{ number_format($item->livro->preco,2) }}</td>
              <td>
                <form action="{{ route('carrinho.atualizar', $item) }}" method="POST" class="flex items-center gap-2">
                  @csrf
                  @method('PUT')
                  <input type="number" name="quantidade" value="{{ $item->quantidade }}" min="1" class="input input-bordered w-20">
                  <button class="btn btn-sm">Atualizar</button>
                </form>
              </td>
              <td>€ {{ number_format($item->sub_total,2) }}</td>
              <td>
                <form action="{{ route('carrinho.remover', $item) }}" method="POST">
                  @csrf
                  @method('DELETE')
                  <button class="btn btn-ghost btn-sm">Remover</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="mt-4 flex justify-end items-center gap-4">
      <div class="text-lg">Total: <strong>€ {{ number_format($total,2) }}</strong></div>
      <a href="{{ route('checkout.address') }}" class="btn btn-primary">Finalizar Compra</a>
    </div>
  @endif
</div>
@endsection
