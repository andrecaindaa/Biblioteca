<div>

<div class="container mx-auto p-6">

    {{-- BOTÃO GOOGLE BOOKS — somente Admin --}}
    @if($isAdmin)
        <div class="flex justify-end mb-6">
            <a href="{{ route('googlebooks.search') }}"
               class="btn btn-success shadow-md hover:shadow-lg transition">
                <i class="fas fa-plus-circle mr-2"></i> Adicionar via Google Books
            </a>
        </div>
    @endif


    {{-- FILTROS --}}
    <div class="flex items-center justify-between mb-6 flex-wrap gap-4">

        <div class="flex space-x-3 flex-wrap gap-3">

            <input wire:model.debounce.300ms="search"
                   type="text"
                   placeholder="Pesquisar por nome ou ISBN..."
                   class="input input-bordered w-64 rounded-lg shadow-sm" />

            <select wire:model="filterEditora"
                    class="select select-bordered rounded-lg shadow-sm">
                <option value="">Todas as editoras</option>
                @foreach($editoras as $ed)
                    <option value="{{ $ed->id }}">{{ $ed->nome }}</option>
                @endforeach
            </select>

            <select wire:model="filterAutor"
                    class="select select-bordered rounded-lg shadow-sm">
                <option value="">Todos os autores</option>
                @foreach($autores as $a)
                    <option value="{{ $a->id }}">{{ $a->nome }}</option>
                @endforeach
            </select>

            <button wire:click="clearFilters"
                    class="btn btn-ghost btn-sm hover:bg-gray-100 rounded-lg">
                Limpar
            </button>
        </div>

        <div class="flex items-center space-x-3">
            <div class="text-sm">Ordenar por:</div>
            <button wire:click="sortBy('nome')" class="btn btn-sm btn-outline rounded-lg">
                Nome @if($sortField === 'nome') ({{ $sortDirection }}) @endif
            </button>
            <button wire:click="sortBy('preco')" class="btn btn-sm btn-outline rounded-lg">
                Preço @if($sortField === 'preco') ({{ $sortDirection }}) @endif
            </button>
        </div>
    </div>


    {{-- GRID DE LIVROS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-7">

        @foreach($livros as $livro)
            <div class="book-card relative bg-white shadow-md rounded-2xl overflow-hidden border border-gray-200 hover:shadow-xl transition-all duration-300">

                {{-- IMAGEM --}}
                <div class="p-4">
                    <div class="book-cover-wrapper relative group">
                        @if($livro->capa)
                            <img src="{{ asset('storage/'.$livro->capa) }}"
                                 alt="{{ $livro->nome }}"
                                 class="book-cover-img rounded-lg group-hover:scale-105 transition-transform duration-300" />
                        @else
                            <div class="book-cover-placeholder rounded-lg">
                                📚 Sem capa
                            </div>
                        @endif
                    </div>
                </div>

                {{-- INFORMAÇÃO --}}
                <div class="px-5 pb-5 pt-0">

                    <h2 class="font-semibold text-md text-gray-900 leading-tight line-clamp-2 min-h-[48px]">
                        {{ $livro->nome }}
                    </h2>


                    <p class="text-sm text-gray-600 mt-1 line-clamp-1">
                        <i class="fas fa-building text-gray-400 mr-1"></i>
                        {{ $livro->editora?->nome ?? '—' }}
                    </p>

                    <p class="text-sm text-gray-700 mt-1 line-clamp-1">
                        <i class="fas fa-user text-gray-400 mr-1"></i>
                        {{ $livro->autores->pluck('nome')->join(', ') }}
                    </p>

                    <div class="mt-4 flex items-center justify-between">
                        @if($livro->isDisponivel())
                            <span class="badge bg-green-500 text-white border-none px-3 py-2 rounded-lg">
                                Disponível
                            </span>
                        @else
                            <span class="badge bg-red-500 text-white border-none px-3 py-2 rounded-lg">
                                Indisponível
                            </span>
                        @endif

                        <a href="{{ route('catalogo.show', $livro->id) }}"
                           class="btn btn-primary btn-sm rounded-lg shadow hover:shadow-md transition">
                           Ver Mais
                        </a>
                    </div>

                </div>

            </div>
        @endforeach

    </div>


    {{-- PAGINAÇÃO --}}
    <div class="mt-8 flex justify-center">
        {{ $livros->links() }}
    </div>

</div>

<style>
.book-cover-wrapper {
    width: 100%;
    aspect-ratio: 2/3;
    overflow: hidden;
    background: #f5f5f5;
}

.book-cover-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.book-cover-placeholder {
    width: 100%;
    height: 100%;
    aspect-ratio: 2/3;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e5e7eb;
    color: #6b7280;
}

.book-card {
    transition: transform .25s, box-shadow .25s;
}

.book-card:hover {
    transform: translateY(-5px);
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-1 {
    display: -webkit-box;
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

</div>
