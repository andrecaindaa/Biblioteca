<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">
        {{ $isEditing ? 'Editar Autor' : 'Novo Autor' }}
    </h1>

    <form wire:submit="save" class="space-y-4">
        <div>
            <label class="label">Nome</label>
            <input type="text" wire:model="nome" class="input input-bordered w-full" />
            @error('nome') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="label">Foto</label>
            <input type="file" wire:model="foto" class="file-input file-input-bordered w-full" />
            @if ($foto)
                <img src="{{ $foto->temporaryUrl() }}" class="w-24 mt-2 rounded">
            @elseif($isEditing && $autorId)
                @php
                    $autorAtual = \App\Models\Autor::find($autorId);
                @endphp
                @if($autorAtual && $autorAtual->foto)
                    <img src="{{ asset('storage/' . $autorAtual->foto) }}" class="w-24 mt-2 rounded">
                @endif
            @endif
        </div>

        <div class="flex gap-4">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('autores.index') }}" class="btn">Cancelar</a>
        </div>
    </form>
</div>
