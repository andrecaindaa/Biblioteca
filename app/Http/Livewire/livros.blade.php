
<div class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-3xl font-bold">Livros</h1>
        <button class="btn btn-success" wire:click="exportExcel">Exportar Excel</button>
    </div>

    <!-- Filtros -->
    <div class="flex gap-4 mb-4">
        <input type="text" placeholder="Pesquisar..." class="input input-bordered flex-1" wire:model.live="search">
        <select class="select select-bordered" wire:model.live="editoraFilter">
            <option value="">Todas Editoras</option>
            @foreach($editoras as $editora)
                <option value="{{ $editora->id }}">{{ $editora->nome }}</option>
            @endforeach
        </select>
        <select class="select select-bordered" wire:model.live="autorFilter">
            <option value="">Todos Autores</option>
            @foreach($autores as $autor)
                <option value="{{ $autor->id }}">{{ $autor->nome }}</option>
            @endforeach
        </select>
    </div>

    <!-- Tabela -->
    <div class="overflow-x-auto bg-base-100 rounded-lg shadow">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th wire:click="sortBy('isbn')" class="cursor-pointer">ISBN @if($sortField==='isbn'){{ $sortDirection==='asc'? '↑':'↓' }}@endif</th>
                    <th wire:click="sortBy('nome')" class="cursor-pointer">Nome @if($sortField==='nome'){{ $sortDirection==='asc'? '↑':'↓' }}@endif</th>
                    <th>Editora</th>
                    <th>Autores</th>
                    <th wire:click="sortBy('preco')" class="cursor-pointer">Preço @if($sortField==='preco'){{ $sortDirection==='asc'? '↑':'↓' }}@endif</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($livros as $livro)
                    <tr>
                        <td>{{ $livro->isbn }}</td>
                        <td>{{ $livro->nome }}</td>
                        <td>{{ $livro->editora->nome ?? '-' }}</td>
                        <td>
                            @foreach($livro->autores as $autor)
                                <span class="badge badge-primary">{{ $autor->nome }}</span>
                            @endforeach
                        </td>
                        <td>{{ number_format($livro->preco,2,',','.') }} €</td>
                        <td class="flex gap-2">
                            <a href="{{ route('livros.edit',$livro) }}" class="btn btn-sm btn-info">Editar</a>
                            <form method="POST" action="{{ route('livros.destroy',$livro) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-error">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <div class="mt-4">
        {{ $livros->links() }}
    </div>
</div>
