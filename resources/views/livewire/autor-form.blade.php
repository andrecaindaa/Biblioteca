<div class="p-6 max-w-2xl mx-auto">
    <h1 class="text-3xl font-bold mb-6">
        {{ $isEditing ? 'Editar Autor' : 'Novo Autor' }}
    </h1>

    <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
            <form wire:submit="save" class="space-y-4">
                <!-- Nome -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Nome do Autor</span>
                    </label>
                    <input
                        type="text"
                        wire:model="nome"
                        class="input input-bordered w-full @error('nome') input-error @enderror"
                        placeholder="Digite o nome do autor"
                    />
                    @error('nome')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror
                </div>

                <!-- Foto -->
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Foto</span>
                    </label>
                    <input
                        type="file"
                        wire:model="foto"
                        class="file-input file-input-bordered w-full @error('foto') file-input-error @enderror"
                        accept="image/*"
                    />
                    @error('foto')
                        <label class="label">
                            <span class="label-text-alt text-error">{{ $message }}</span>
                        </label>
                    @enderror

                    <!-- Preview da imagem -->
                    @if ($foto)
                        <div class="mt-4">
                            <p class="text-sm text-base-600 mb-2">Pré-visualização da nova foto:</p>
                            <img src="{{ $foto->temporaryUrl() }}" class="w-32 h-32 object-cover rounded-lg border">
                        </div>
                    @elseif($isEditing)
                        @php
                            $autorAtual = \App\Models\Autor::find($autorId);
                        @endphp
                        @if($autorAtual && $autorAtual->foto)
                            <div class="mt-4">
                                <p class="text-sm text-base-600 mb-2">Foto atual:</p>
                                <img src="{{ asset('storage/' . $autorAtual->foto) }}"
                                     class="w-32 h-32 object-cover rounded-lg border">
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Botões -->
                <div class="form-control mt-6">
                    <div class="flex gap-3 justify-end">
                        <a href="{{ route('admin.autores.index') }}" class="btn btn-ghost">
                            Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            @if($isEditing)
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Atualizar Autor
                            @else
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Criar Autor
                            @endif
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div wire:loading class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="loading loading-spinner loading-lg text-primary"></div>
    </div>
</div>
