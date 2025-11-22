@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Requisitar Livro: {{ $livro->nome }}</h1>

@if ($errors->any())
<div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form method="POST" action="{{ route('requisicoes.store', $livro) }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-4">
        <label class="block mb-2 font-bold">Foto do Cidadão</label>
        <input type="file" name="foto_cidadao" class="input input-bordered w-full" required>
    </div>
    <button type="submit" class="btn btn-primary">Requisitar Livro</button>
</form>
@endsection
