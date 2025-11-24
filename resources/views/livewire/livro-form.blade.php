<div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-4">{{ $isEditing ? 'Editar Livro' : 'Novo Livro' }}</h1>

    <form wire:submit="save" class="space-y-4">
        <div>
            <label class="label">Nome</label>
            <input type="text" class="input input-bordered w-full" wire:model="nome">
            @error('nome') <span class="text-error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="label">ISBN</label>
            <input type="text" class="input input-bordered w-full" wire:model="isbn">
            @error('isbn') <span class="text-error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="label">Preço (€)</label>
            <input type="number" step="0.01" class="input input-bordered w-full" wire:model="preco">
            @error('preco') <span class="text-error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="label">Editora</label>
            <select class="select select-bordered w-full" wire:model="editora_id">
                <option value="">Selecione a editora</option>
                @foreach($editoras as $editora)
                    <option value="{{ $editora->id }}">{{ $editora->nome }}</option>
                @endforeach
            </select>
            @error('editora_id') <span class="text-error">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="label">Autores</label>
            <select multiple class="select select-bordered w-full" wire:model="autoresSelecionados">
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
            @elseif($isEditing && $livroId)
                @php
                    $livroAtual = \App\Models\Livro::find($livroId);
                @endphp
                @if($livroAtual && $livroAtual->imagem_capa)
                    <img src="{{ asset('storage/' . $livroAtual->imagem_capa) }}" class="mt-2 h-24 rounded-xl"/>
                @endif
            @endif
        </div>

        <div>
            <label class="label">Bibliografia</label>
            <textarea class="textarea textarea-bordered w-full" wire:model="bibliografia" rows="4"></textarea>
            @error('bibliografia') <span class="text-error">{{ $message }}</span> @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit" class="btn btn-primary flex-1">
                {{ $isEditing ? 'Atualizar' : 'Criar' }} Livro
            </button>
            <a href="{{ route('admin.livros.index') }}" class="btn btn-ghost">Cancelar</a>
        </div>
    </form>

    <!-- Loading -->
    <div wire:loading class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="loading loading-spinner loading-lg text-primary"></div>
    </div>
</div>
