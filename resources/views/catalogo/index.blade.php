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

    <!-- GRID COM IMAGENS DE DIMENSÕES IGUAIS -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        @forelse($livros as $livro)
            <div class="col">
                <div class="card h-100 shadow-sm book-card">
                    <!-- Container da imagem com dimensões fixas -->
                    <div class="book-image-wrapper">
                        @if($livro->imagem_capa)
                            <img src="{{ asset('storage/'.$livro->imagem_capa) }}"
                                 class="book-image"
                                 alt="Capa de {{ $livro->nome }}">
                        @else
                            <div class="book-image-placeholder">
                                <span class="text-muted">📚 Sem capa</span>
                            </div>
                        @endif
                    </div>

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold">{{ Str::limit($livro->nome, 45) }}</h5>
                        <p class="card-text text-muted small mb-2">
                            Autor(es): {{ $livro->autores->pluck('nome')->join(', ') }}
                        </p>
                        <p class="mb-3">
                            @if($livro->isDisponivel())
                                <span class="badge bg-success">Disponível</span>
                            @else
                                <span class="badge bg-danger">Requisitado</span>
                            @endif
                        </p>
                        <div class="mt-auto d-flex gap-2">
                            <a href="{{ route('catalogo.show', $livro->id) }}" class="btn btn-outline-primary btn-sm flex-fill">Ver</a>
                            @if($livro->isDisponivel() && auth()->check() && !auth()->user()->isAdmin())
                                <a href="{{ route('users.requisitar.form', $livro->id) }}" class="btn btn-success btn-sm flex-fill">
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

    <!-- Paginação -->
    @if($livros->hasPages())
        <div class="mt-4">
            {{ $livros->links() }}
        </div>
    @endif
</div>

<style>
.book-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: none;
    border-radius: 12px;
}

.book-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

/* Container da imagem com dimensões fixas */
.book-image-wrapper {
    width: 100%;
    height: 280px; /* Altura fixa para todas as imagens */
    overflow: hidden;
    position: relative;
}

/* Imagem que preenche o container mantendo proporção */
.book-image {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Isso é crucial - mantém proporção e preenche o container */
    transition: transform 0.3s ease;
}

.book-card:hover .book-image {
    transform: scale(1.05);
}

/* Placeholder para livros sem imagem */
.book-image-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #6c757d;
    font-size: 0.9rem;
}

/* Ajustes para cards */
.card-body {
    padding: 1.25rem;
}

.card-title {
    font-size: 1rem;
    line-height: 1.4;
    min-height: 2.8rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Responsividade */
@media (max-width: 768px) {
    .book-image-wrapper {
        height: 240px; /* Um pouco menor em mobile */
    }

    .row-cols-md-3 {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 576px) {
    .book-image-wrapper {
        height: 220px;
    }

    .row-cols-sm-2 {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection
