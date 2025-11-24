<div class="p-6">
    <!-- Cabeçalho -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-3xl font-bold">Gestão de Autores</h1>
        <a href="{{ route('admin.autores.create') }}" class="btn btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Novo Autor
        </a>
    </div>

    <!-- Barra de Pesquisa -->
    <div class="card bg-base-200 p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-4 items-center">
            <div class="form-control flex-1">
                <input
                    type="text"
                    placeholder="Pesquisar autores..."
                    class="input input-bordered w-full"
                    wire:model.live="search"
                >
            </div>
            <div class="text-sm text-base-600">
                {{ $autores->total() }} autor(es) encontrado(s)
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
                        Nome
                        @if($sortField === 'nome')
                            @if($sortDirection === 'asc') ↑ @else ↓ @endif
                        @endif
                    </th>
                    <th>Foto</th>
                    <th class="text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($autores as $autor)
                    <tr>
                        <td class="font-semibold">{{ $autor->nome }}</td>
                        <td>
                            @if ($autor->foto)
                                <div class="avatar">
                                    <div class="w-12 h-12 rounded">
                                        <img src="{{ asset('storage/' . $autor->foto) }}"
                                             alt="{{ $autor->nome }}"
                                             class="object-cover">
                                    </div>
                                </div>
                            @else
                                <div class="avatar placeholder">
                                    <div class="bg-neutral text-neutral-content rounded w-12">
                                        <span class="text-xs">Sem foto</span>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="flex gap-2 justify-end">
                                <a href="{{ route('admin.autores.edit', $autor->id) }}"
                                   class="btn btn-sm btn-info">
                                    Editar
                                </a>
                                <button
                                    wire:click="delete({{ $autor->id }})"
                                    wire:confirm="Tem a certeza que deseja eliminar o autor '{{ $autor->nome }}'?"
                                    class="btn btn-sm btn-error">
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-8">
                            <div class="flex flex-col items-center justify-center text-base-500">
                                <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <p class="text-lg">Nenhum autor encontrado</p>
                                @if($search)
                                    <p class="text-sm mt-2">Tente alterar os termos da pesquisa</p>
                                @else
                                    <p class="text-sm mt-2">Clique em "Novo Autor" para adicionar o primeiro</p>
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
        {{ $autores->links() }}
    </div>
</div>
