@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Requisitar Livro: {{ $livro->nome }}</h1>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('users.requisitar', $livro->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="foto_cidadao" class="form-label">Foto do Cidadão (opcional)</label>
            <input type="file" name="foto_cidadao" id="foto_cidadao" class="form-control" accept="image/*">
            @error('foto_cidadao')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="data_prevista_entrega" class="form-label">Data Prevista de Entrega</label>
            <input type="date" name="data_prevista_entrega" id="data_prevista_entrega" class="form-control" required>
            @error('data_prevista_entrega')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Requisitar</button>
        <a href="{{ route('users.show', auth()->id()) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
