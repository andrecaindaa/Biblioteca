<div class="container mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <div class="flex space-x-3">
            <input wire:model.debounce.300ms="search" type="text" placeholder="Pesquisar por nome ou ISBN..." class="input input-bordered" />

            <select wire:model="filterEditora" class="select select-bordered">
                <option value="">Todas as editoras</option>
                @foreach($editoras as $ed)
                    <option value="{{ $ed->id }}">{{ $ed->nome }}</option>
                @endforeach
            </select>

            <select wire:model="filterAutor" class="select select-bordered">
                <option value="">Todos os autores</option>
                @foreach($autores as $a)
                    <option value="{{ $a->id }}">{{ $a->nome }}</option>
                @endforeach
            </select>

            <button wire:click="clearFilters" class="btn btn-ghost">Limpar</button>
        </div>

        <div class="flex items-center space-x-3">
            <div class="text-sm">Ordenar por</div>
            <button wire:click="sortBy('nome')" class="btn btn-sm">
                Nome @if($sortField === 'nome') ({{ $sortDirection }}) @endif
            </button>
            <button wire:click="sortBy('preco')" class="btn btn-sm">
                Preço @if($sortField === 'preco') ({{ $sortDirection }}) @endif
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($livros as $livro)
            <div class="card bg-base-100 shadow">
                <figure class="p-4">
                    @if($livro->imagem_capa)
                        <img src="{{ asset('storage/'.$livro->imagem_capa) }}" alt="{{ $livro->nome }}" class="h-48 w-full object-cover rounded" />
                    @else
                        <div class="h-48 w-full bg-base-200 rounded flex items-center justify-center text-lg">Sem capa</div>
                    @endif
                </figure>
                <div class="card-body">
                    <h2 class="card-title">{{ $livro->nome }}</h2>
                    <p class="text-sm text-base-content/70">
                        {{ $livro->editora?->nome ?? '—' }}
                    </p>
                    <p class="text-sm">
                        @foreach($livro->autores as $autor)
                            <span class="mr-1 text-xs">{{ $autor->nome }}</span>@if(!$loop->last),@endif
                        @endforeach
                    </p>

                    <div class="mt-3 flex items-center justify-between">
                        @if($livro->isDisponivel())
                            <span class="badge badge-success">Disponível</span>
                        @else
                            <span class="badge badge-error">Indisponível</span>
                        @endif

                        <a href="{{ route('catalogo.show', $livro->id) }}" class="btn btn-sm btn-primary">Ver Mais</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $livros->links() }}
    </div>
</div>
