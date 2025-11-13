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
            <a href="{{ route('livros.create') }}" class="btn btn-primary">+ Novo Livro</a>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success mb-4">{{ session('message') }}</div>
    @endif

    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>ISBN</th>
                    <th>Editora</th>
                    <th>Preço</th>
                    <th class="text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($livros as $livro)
                    <tr>
                        <td>{{ $livro->nome }}</td>
                        <td>{{ $livro->isbn }}</td>
                        <td>{{ $livro->editora?->nome }}</td>
                        <td>{{ number_format($livro->preco, 2, ',', '.') }} €</td>
                        <td class="text-right">
                            <a href="{{ route('livros.edit', $livro->id) }}" class="btn btn-sm btn-info">Editar</a>
                            <button wire:click="delete({{ $livro->id }})" class="btn btn-sm btn-error">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Nenhum livro encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $livros->links() }}
    </div>
</div>

