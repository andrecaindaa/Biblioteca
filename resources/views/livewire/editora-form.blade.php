<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">
        {{ $editora ? 'Editar Editora' : 'Nova Editora' }}
    </h1>

    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label class="label">Nome</label>
            <input type="text" wire:model="nome" class="input input-bordered w-full">
            @error('nome') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
    </form>
</div>
