<div class="p-6">
    <!-- Cabeçalho -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-3xl font-bold">Gestão de Editoras</h1>
        <a href="{{ route('editoras.create') }}" class="btn btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nova Editora
        </a>
    </div>

    <!-- Barra de Pesquisa -->
    <div class="card bg-base-200 p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-4 items-center">
            <div class="form-control flex-1">
                <input
                    type="text"
                    placeholder="Pesquisar editoras..."
                    class="input input-bordered w-full"
                    wire:model.live="search"
                >
            </div>
            <div class="text-sm text-base-600">
                {{ $editoras->total() }} editora(s) encontrada(s)
            </div>
        </div>
    </div>

    <!-- Mensagem de Sucesso -->
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
                    <th class="w-8/12">Nome</th>
                    <th class="w-2/12">Logotipo</th>
                    <th class="w-2/12 text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($editoras as $editora)
                    <tr>
                        <td class="font-semibold">{{ $editora->nome }}</td>
                        <td>
                            @if ($editora->logotipo)
                                <div class="avatar">
                                    <div class="w-12 h-12 rounded">
                                        <img src="{{ asset('storage/' . $editora->logotipo) }}"
                                             alt="{{ $editora->nome }}"
                                             class="object-contain">
                                    </div>
                                </div>
                            @else
                                <div class="avatar placeholder">
                                    <div class="bg-neutral text-neutral-content rounded w-12">
                                        <span class="text-xs">Sem logo</span>
                                    </div>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="flex gap-2 justify-end">
                                <a href="{{ route('editoras.edit', $editora->id) }}"
                                   class="btn btn-sm btn-info">
                                    Editar
                                </a>
                                <button
                                    wire:click="delete({{ $editora->id }})"
                                    wire:confirm="Tem a certeza que deseja eliminar a editora '{{ $editora->nome }}'?"
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="text-lg">Nenhuma editora encontrada</p>
                                @if($search)
                                    <p class="text-sm mt-2">Tente alterar os termos da pesquisa</p>
                                @else
                                    <p class="text-sm mt-2">Clique em "Nova Editora" para adicionar a primeira</p>
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
        {{ $editoras->links() }}
    </div>
</div>
