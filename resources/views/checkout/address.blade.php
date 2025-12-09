@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
  <h1 class="text-2xl mb-4">Morada de Entrega</h1>

  <form action="{{ route('checkout.address.store') }}" method="POST">
    @csrf
    <div class="form-control">
      <label class="label"><span class="label-text">Morada completa</span></label>
      <textarea name="morada" class="textarea textarea-bordered w-full" rows="4" required>{{ old('morada', auth()->user()->address ?? '') }}</textarea>
    </div>

    <div class="mt-4">
      <button class="btn btn-primary">Continuar para Pagamento</button>
    </div>
  </form>
</div>
@endsection
