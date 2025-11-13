<div class="p-6">
    <!-- Cabeçalho com Botão Exportar -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-3xl font-bold">Gestão de Livros</h1>
        <div class="flex gap-2">
            <!-- Botão Exportar Excel -->
            <button wire:click="exportExcel" class="btn btn-success">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Exportar Excel
            </button>
            <a href="{{ route('livros.create') }}" class="btn btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Novo Livro
            </a>
        </div>
    </div>

    <!-- Barra de Pesquisa -->
    <div class="card bg-base-200 p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-4 items-center">
            <div class="form-control flex-1">
                <input
                    type="text"
                    placeholder="Pesquisar por título ou ISBN..."
                    class="input input-bordered w-full"
                    wire:model.live="search"
                >
            </div>
            <div class="text-sm text-base-600">
                {{ $livros->total() }} livro(s) encontrado(s)
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success mb-6 shadow-lg">
            <div>
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('message') }}</span>
            </div>
        </div>
    @endif

    <!-- Tabela -->
    <div class="overflow-x-auto bg-base-100 rounded-lg shadow">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-base-300">
                    <th wire:click="sortBy('nome')" class="cursor-pointer hover:bg-base-200">
                        Título
                        @if($sortField === 'nome')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th wire:click="sortBy('isbn')" class="cursor-pointer hover:bg-base-200">
                        ISBN
                        @if($sortField === 'isbn')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th>Editora</th>
                    <th wire:click="sortBy('preco')" class="cursor-pointer hover:bg-base-200">
                        Preço
                        @if($sortField === 'preco')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th class="text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($livros as $livro)
                    <tr>
                        <td class="font-semibold">{{ $livro->nome }}</td>
                        <td class="font-mono text-sm">{{ $livro->isbn }}</td>
                        <td>
                            @if($livro->editora)
                                <span class="badge badge-outline">{{ $livro->editora->nome }}</span>
                            @else
                                <span class="text-base-400 text-sm">Sem editora</span>
                            @endif
                        </td>
                        <td class="font-mono">{{ number_format($livro->preco, 2, ',', '.') }} €</td>
                        <td>
                            <div class="flex gap-2 justify-end">
                                <a href="{{ route('livros.edit', $livro->id) }}" class="btn btn-sm btn-info">
                                    Editar
                                </a>
                                <button
                                    wire:click="delete({{ $livro->id }})"
                                    wire:confirm="Tem a certeza que deseja eliminar o livro '{{ $livro->nome }}'?"
                                    class="btn btn-sm btn-error">
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-8">
                            <div class="flex flex-col items-center justify-center text-base-500">
                                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <p class="text-lg">Nenhum livro encontrado</p>
                                @if($search)
                                    <p class="text-sm mt-2">Tente alterar os termos da pesquisa</p>
                                @else
                                    <p class="text-sm mt-2">Clique em "Novo Livro" para adicionar o primeiro</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <div class="mt-6">
        {{ $livros->links() }}
    </div>
</div>
