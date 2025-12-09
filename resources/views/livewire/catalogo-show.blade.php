<div class="container mx-auto p-6 max-w-4xl">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- CAPA --}}
        <div class="col-span-1">
            @if($livro->imagem_capa)
                <img src="{{ asset('storage/'.$livro->imagem_capa) }}"
                     alt="{{ $livro->nome }}"
                     class="w-full rounded-md object-cover" />
            @else
                <div class="h-64 w-full bg-base-200 rounded flex items-center justify-center">
                    Sem capa
                </div>
            @endif
        </div>

        <div class="col-span-2">

            {{-- TÍTULO / INFO --}}
            <h1 class="text-2xl font-bold">{{ $livro->nome }}</h1>
            <p class="text-sm text-base-600">ISBN: {{ $livro->isbn }}</p>
            <p class="text-sm mt-2">Editora: {{ $livro->editora?->nome }}</p>

            <p class="mt-2">
                Autores:
                @foreach($livro->autores as $autor)
                    <span class="text-sm">{{ $autor->nome }}</span>@if(!$loop->last),@endif
                @endforeach
            </p>

            {{-- PREÇO --}}
            <p class="mt-4">
                <strong>Preço:</strong>
                {{ number_format($livro->preco ?? 0, 2, ',', '.') }} €
            </p>

            {{-- BIBLIOGRAFIA --}}
            <div class="mt-4">
                <h4 class="font-semibold">Bibliografia</h4>
                <div class="mt-2 prose">
                    {!! nl2br(e($livro->bibliografia)) !!}
                </div>
            </div>

            {{-- DISPONIBILIDADE --}}
            <div class="mt-4">
                @if($livro->isDisponivel())
                    <span class="badge badge-success">Disponível</span>
                @else
                    <span class="badge badge-error">Indisponível</span>
                @endif
            </div>

            {{-- BOTÃO ADICIONAR AO CARRINHO (sempre para cidadãos autenticados) --}}
            @if(auth()->check() && !auth()->user()->isAdmin())
                <form action="{{ route('carrinho.adicionar', $livro->id) }}"
                      method="POST"
                      class="mt-4">
                    @csrf
                    <button type="submit" class="btn btn-accent w-full">
                        🛒 Adicionar ao Carrinho
                    </button>
                </form>
            @endif

            {{-- REQUISIÇÃO (apenas quando disponível e para cidadãos) --}}
            <div class="mt-6">

                @auth

                    @if($livro->isDisponivel())

                        {{-- LIVRO DISPONÍVEL — REQUISIÇÃO --}}
                        <div class="card p-4 bg-base-200 mt-4">
                            <h4 class="font-semibold mb-2">Requisitar este livro</h4>

                            @if (session()->has('error'))
                                <div class="alert alert-error mb-3">{{ session('error') }}</div>
                            @endif
                            @if (session()->has('success'))
                                <div class="alert alert-success mb-3">{{ session('success') }}</div>
                            @endif

                            <div class="mb-3">
                                <label class="label">Foto do Cidadão (opcional)</label>
                                <input type="file" wire:model="foto_cidadao" accept="image/*"
                                       class="file-input file-input-bordered w-full" />

                                @error('foto_cidadao')
                                    <span class="text-error">{{ $message }}</span>
                                @enderror

                                @if ($foto_cidadao)
                                    <img src="{{ $foto_cidadao->temporaryUrl() }}" class="mt-2 h-24 rounded" />
                                @endif
                            </div>

                            <div class="flex items-center space-x-2">
                                <button wire:click="requisitar"
                                        wire:loading.attr="disabled"
                                        class="btn btn-primary">
                                    Requisitar (Confirmar)
                                </button>

                                <a href="{{ route('catalogo.index') }}" class="btn btn-ghost">
                                    Voltar ao Catálogo
                                </a>
                            </div>
                        </div>

                    @else

                        {{-- LIVRO INDISPONÍVEL --}}
                        <div class="alert alert-warning mt-4">
                            Este livro está atualmente indisponível.
                        </div>

                        @if(!auth()->user()->isAdmin())
                            <button wire:click="ativarAlerta" class="btn btn-outline-primary mt-3">
                                Avisar-me quando estiver disponível
                            </button>

                            @if (session()->has('alerta_success'))
                                <div class="alert alert-success mt-2">
                                    {{ session('alerta_success') }}
                                </div>
                            @endif
                        @endif

                    @endif

                @else
                    <a href="{{ route('login') }}" class="btn btn-primary mt-4">
                        Iniciar Sessão para Requisitar
                    </a>
                @endauth

            </div>

            {{-- HISTÓRICO --}}
            <div class="mt-10">
                <h4 class="font-semibold mb-3">Histórico de Requisições (últimos registros)</h4>

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
                                        <span class="badge">
                                            {{ ucfirst($r->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            {{-- REVIEWS --}}
            <div class="mt-10">
                <h4 class="font-semibold mb-3">Avaliações dos Leitores</h4>

                @php
                    $reviewsAtivos = \App\Models\Review::where('livro_id', $livro->id)
                        ->where('status', 'ativo')
                        ->with('user')
                        ->latest()
                        ->get();
                @endphp

                @if($reviewsAtivos->isEmpty())
                    <p>Ainda não existem reviews para este livro.</p>
                @else
                    @foreach($reviewsAtivos as $review)
                        <div class="card bg-base-200 p-4 mb-3">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold">{{ $review->user->name }}</span>
                                <span class="text-sm opacity-70">
                                    {{ $review->created_at->format('d/m/Y') }}
                                </span>
                            </div>

                            @if($review->rating)
                                <div class="mt-2 text-warning">
                                    ⭐ {{ $review->rating }}/5
                                </div>
                            @endif

                            <p class="mt-2">{{ $review->comentario }}</p>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- LIVROS RELACIONADOS --}}
            <div class="mt-10">
                <h4 class="font-semibold mb-3">Livros relacionados</h4>

                @if($relatedBooks->isEmpty())
                    <p class="text-sm text-muted">Sem sugestões por enquanto.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($relatedBooks as $r)
                            <div class="card p-3">
                                @if($r->imagem_capa)
                                    <img src="{{ asset('storage/'.$r->imagem_capa) }}"
                                        alt="{{ $r->nome }}"
                                        class="w-full h-40 object-cover rounded mb-2" />
                                @endif

                                <h5 class="font-semibold text-sm">
                                    {{ Str::limit($r->nome, 60) }}
                                </h5>

                                <p class="text-xs text-muted mt-1">
                                    {{ Str::limit(strip_tags($r->bibliografia), 120) }}
                                </p>

                                <div class="mt-3">
                                    <a href="{{ route('catalogo.show', $r->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        Ver livro
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
