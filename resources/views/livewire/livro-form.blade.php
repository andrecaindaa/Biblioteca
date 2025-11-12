<div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-4">{{ $livro ? 'Editar Livro' : 'Novo Livro' }}</h1>

    <form wire:submit.prevent="save" class="space-y-4">
        <div>
            <label class="label">Nome</label>
            <input type="text" class="input input-bordered w-full" wire:model.defer="nome">
            @error('nome') <span class="text-error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="label">ISBN</label>
            <input type="text" class="input input-bordered w-full" wire:model.defer="isbn">
            @error('isbn') <span class="text-error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="label">Preço (€)</label>
            <input type="number" step="0.01" class="input input-bordered w-full" wire:model.defer="preco">
            @error('preco') <span class="text-error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="label">Editora</label>
            <select class="select select-bordered w-full" wire:model.defer="editora_id">
                <option value="">Selecione a editora</option>
                @foreach($editoras as $editora)
                    <option value="{{ $editora->id }}">{{ $editora->nome }}</option>
                @endforeach
            </select>
            @error('editora_id') <span class="text-error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="label">Autores</label>
            <select multiple class="select select-bordered w-full" wire:model.defer="autoresSelecionados">
                @foreach($autores as $autor)
                    <option value="{{ $autor->id }}">{{ $autor->nome }}</option>
                @endforeach
            </select>
            @error('autoresSelecionados') <span class="text-error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="label">Imagem da Capa</label>
            <input type="file" wire:model="imagem_capa" class="file-input file-input-bordered w-full">
            @if ($imagem_capa)
                <img src="{{ $imagem_capa->temporaryUrl() }}" class="mt-2 h-24 rounded-xl"/>
            @elseif($livro && $livro->imagem_capa)
                <img src="{{ Storage::url($livro->imagem_capa) }}" class="mt-2 h-24 rounded-xl"/>
            @endif
        </div>

        <div>
            <label class="label">Bibliografia</label>
            <textarea class="textarea textarea-bordered w-full" wire:model.defer="livro.bibliografia">{{ old('bibliografia') }}</textarea>
            {{-- Note: bibliografia é cifrada por mutator no model --}}
        </div>

        <button type="submit" class="btn btn-primary">{{ $livro ? 'Atualizar' : 'Criar' }}</button>
    </form>
</div>
