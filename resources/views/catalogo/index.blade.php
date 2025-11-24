@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h1 class="fw-bold mb-4 text-center">📚 Catálogo de Livros</h1>

    <!-- FILTROS -->
    <form method="GET" action="{{ route('catalogo.index') }}" class="mb-4">
        <div class="row g-3">
            <div class="col-md-4">
                <select name="autor" class="form-select">
                    <option value="">— Todos os autores —</option>
                    @foreach($autores as $autor)
                        <option value="{{ $autor->id }}" {{ request('autor') == $autor->id ? 'selected' : '' }}>
                            {{ $autor->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="editora" class="form-select">
                    <option value="">— Todas as editoras —</option>
                    @foreach($editoras as $editora)
                        <option value="{{ $editora->id }}" {{ request('editora') == $editora->id ? 'selected' : '' }}>
                            {{ $editora->nome }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="disponibilidade" class="form-select">
                    <option value="">— Todas —</option>
                    <option value="disponivel" {{ request('disponibilidade')=='disponivel'?'selected':'' }}>Disponível</option>
                    <option value="indisponivel" {{ request('disponibilidade')=='indisponivel'?'selected':'' }}>Requisitado</option>
                </select>
            </div>
        </div>
        <div class="mt-3 text-end">
            <button class="btn btn-primary">Filtrar</button>
            <a href="{{ route('catalogo.index') }}" class="btn btn-secondary">Limpar</a>
        </div>
    </form>

    <!-- GRID 3 COLUNAS -->
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @forelse($livros as $livro)
            <div class="col">
                <div class="card h-100 shadow-sm book-card">
                    @if($livro->imagem_capa)
                        <img src="{{ asset('storage/'.$livro->imagem_capa) }}" class="card-img-top" style="height:230px;object-fit:cover" alt="Capa">
                    @else
                        <div class="bg-light d-flex justify-content-center align-items-center" style="height:230px">
                            <span class="text-muted small">Sem capa</span>
                        </div>
                    @endif
                    <div class="card-body d-flex flex-column">
                        <h5 class="fw-bold">{{ Str::limit($livro->nome,45) }}</h5>
                        <p class="text-muted small">Autor(es): {{ $livro->autores->pluck('nome')->join(', ') }}</p>
                        <p>
                            @if($livro->isDisponivel())
                                <span class="badge bg-success">Disponível</span>
                            @else
                                <span class="badge bg-danger">Requisitado</span>
                            @endif
                        </p>
                        <div class="mt-auto d-flex justify-content-between">
                            <a href="{{ route('catalogo.show',$livro->id) }}" class="btn btn-outline-primary btn-sm">Ver</a>
                            @if($livro->isDisponivel() && auth()->check() && !auth()->user()->isAdmin()!== true)
                                <a href="{{ route('users.requisitar.form', $livro->id) }}" class="btn btn-success btn-sm">
                                    Requisitar
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <h4 class="text-muted">Nenhum livro encontrado.</h4>
            </div>
        @endforelse
    </div>

</div>

<style>
.book-card { border-radius:10px; transition:transform .2s ease; }
.book-card:hover { transform: translateY(-5px); box-shadow:0 8px 20px rgba(0,0,0,.15); }
</style>
@endsection
