<div class="p-6">
    <div class="flex justify-between mb-4">
        <input type="text" wire:model.live="search" placeholder="Pesquisar autores..." class="input input-bordered w-1/3" />
        <a href="{{ route('autores.create') }}" class="btn btn-primary">+ Novo Autor</a>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success mb-4">{{ session('message') }}</div>
    @endif

    <div class="overflow-x-auto">
        <table class="table w-full">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Foto</th>
                    <th class="text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($autores as $autor)
                    <tr>
                        <td>{{ $autor->nome }}</td>
                        <td>
                            @if ($autor->foto)
                                <img src="{{ asset('storage/' . $autor->foto) }}" alt="Foto" class="w-12 h-12 rounded">
                            @endif
                        </td>
                        <td class="text-right">
                            <a href="{{ route('autores.edit', $autor->id) }}" class="btn btn-sm btn-info">Editar</a>
                            <button wire:click="delete({{ $autor->id }})" class="btn btn-sm btn-error">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Nenhum autor encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $autores->links() }}
    </div>
</div>
