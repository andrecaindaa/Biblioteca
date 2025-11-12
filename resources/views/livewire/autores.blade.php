<div class="p-6">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-3xl font-bold">Autores</h1>
        <a href="{{ route('autores.create') }}" class="btn btn-primary">Novo Autor</a>
    </div>

    <!-- Pesquisa -->
    <div class="mb-4">
        <input type="text" placeholder="Pesquisar por nome..." class="input input-bordered w-full" wire:model.live="search">
    </div>

    <!-- Tabela -->
    <div class="overflow-x-auto bg-base-100 rounded-lg shadow">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th wire:click="sortBy('nome')" class="cursor-pointer">Nome @if($sortField==='nome'){{ $sortDirection==='asc'? '↑':'↓' }}@endif</th>
                    <th wire:click="sortBy('email')" class="cursor-pointer">Email @if($sortField==='email'){{ $sortDirection==='asc'? '↑':'↓' }}@endif</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($autores as $autor)
                    <tr>
                        <td>{{ $autor->nome }}</td>
                        <td>{{ $autor->email }}</td>
                        <td class="flex gap-2">
                            <a href="{{ route('autores.edit',$autor) }}" class="btn btn-sm btn-info">Editar</a>
                            <form method="POST" action="{{ route('autores.destroy',$autor) }}">
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

    <div class="mt-4">
        {{ $autores->links() }}
    </div>
</div>
