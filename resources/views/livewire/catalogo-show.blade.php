<div class="container mx-auto p-6 max-w-4xl">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="col-span-1">
            @if($livro->imagem_capa)
                <img src="{{ asset('storage/'.$livro->imagem_capa) }}" alt="{{ $livro->nome }}" class="w-full rounded-md object-cover" />
            @else
                <div class="h-64 w-full bg-base-200 rounded flex items-center justify-center">Sem capa</div>
            @endif
        </div>

        <div class="col-span-2">
            <h1 class="text-2xl font-bold">{{ $livro->nome }}</h1>
            <p class="text-sm text-base-600">ISBN: {{ $livro->isbn }}</p>
            <p class="text-sm mt-2">Editora: {{ $livro->editora?->nome }}</p>
            <p class="mt-2">
                Autores:
                @foreach($livro->autores as $autor)
                    <span class="text-sm">{{ $autor->nome }}</span>@if(!$loop->last),@endif
                @endforeach
            </p>

            <p class="mt-4"><strong>Preço:</strong> {{ number_format($livro->preco ?? 0, 2, ',', '.') }} €</p>

            <div class="mt-4">
                <h4 class="font-semibold">Bibliografia</h4>
                <div class="mt-2 prose">
                    {!! nl2br(e($livro->bibliografia)) !!}
                </div>
            </div>

            <div class="mt-6">
                @if($livro->isDisponivel())
                    <span class="badge badge-success">Disponível</span>
                @else
                    <span class="badge badge-error">Indisponível</span>
                @endif
            </div>

            <div class="mt-6">
                @auth
                    @if($livro->isDisponivel())
                        <div class="card p-4 bg-base-200">
                            <h4 class="font-semibold mb-2">Requisitar este livro</h4>

                            @if (session()->has('error'))
                                <div class="alert alert-error mb-3">{{ session('error') }}</div>
                            @endif
                            @if (session()->has('success'))
                                <div class="alert alert-success mb-3">{{ session('success') }}</div>
                            @endif

                            <div class="mb-3">
                                <label class="label">Foto do Cidadão (opcional)</label>
                                <input type="file" wire:model="foto_cidadao" accept="image/*" class="file-input file-input-bordered w-full" />
                                @error('foto_cidadao') <span class="text-error">{{ $message }}</span> @enderror
                                @if ($foto_cidadao)
                                    <img src="{{ $foto_cidadao->temporaryUrl() }}" class="mt-2 h-24 rounded" />
                                @endif
                            </div>

                            <div class="flex items-center space-x-2">
                                <button wire:click="requisitar" wire:loading.attr="disabled" class="btn btn-primary">
                                    Requisitar (Confirmar)
                                </button>
                                <a href="{{ route('catalogo.index') }}" class="btn btn-ghost">Voltar ao Catálogo</a>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning">Este livro está atualmente indisponível.</div>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary">Iniciar Sessão para Requisitar</a>
                @endauth
            </div>

            <div class="mt-8">
                <h4 class="font-semibold mb-2">Histórico de Requisições (últimos registros)</h4>
                @if($historico->isEmpty())
                    <p>Sem histórico.</p>
                @else
                    <table class="table w-full">
                        <thead>
                            <tr>
                                <th>Nº</th>
                                <th>Cidadão</th>
                                <th>Data Req.</th>
                                <th>Prevista</th>
                                <th>Real</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($historico as $r)
                                <tr>
                                    <td>{{ $r->numero }}</td>
                                    <td>{{ $r->user?->name }}</td>
                                    <td>{{ $r->data_requisicao?->format('d/m/Y') }}</td>
                                    <td>{{ $r->data_prevista_entrega?->format('d/m/Y') }}</td>
                                    <td>{{ $r->data_entrega_real ? $r->data_entrega_real->format('d/m/Y') : '-' }}</td>
                                    <td>
                                        <span class="badge">{{ ucfirst($r->status) }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</div>
