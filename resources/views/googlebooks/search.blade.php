@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Pesquisar Livros na Google Books</h1>

    <!-- Mensagens de Sucesso/Erro -->
    @if(session('success'))
        <div class="alert alert-success mb-4">
            <div class="flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-6 h-6 mx-2 stroke-current">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                </svg>
                <label>{{ session('success') }}</label>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error mb-4">
            <div class="flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="w-6 h-6 mx-2 stroke-current">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                </svg>
                <label>{{ session('error') }}</label>
            </div>
        </div>
    @endif

    <form action="{{ route('googlebooks.search') }}" method="GET" class="mb-6">
        <div class="flex gap-2">
            <input type="text" name="q" placeholder="Digite o título, autor ou ISBN..."
                   value="{{ $query ?? '' }}"
                   class="input input-bordered w-full max-w-md">
            <button type="submit" class="btn btn-primary">Pesquisar</button>
        </div>
    </form>

    @if(isset($books) && count($books) > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($books as $book)
                <div class="card bg-base-100 shadow-xl">
                    @if($book['imagem_capa'])
                        <figure class="h-48 overflow-hidden">
                            <img src="{{ $book['imagem_capa'] }}"
                                 alt="Capa de {{ $book['nome'] }}"
                                 class="w-full h-full object-cover">
                        </figure>
                    @else
                        <figure class="h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500">Sem imagem</span>
                        </figure>
                    @endif

                    <div class="card-body">
                        <h2 class="card-title text-lg">{{ Str::limit($book['nome'], 50) }}</h2>

                        <div class="space-y-2">
                            @if(!empty($book['autores']))
                                <p><strong>Autores:</strong> {{ implode(', ', $book['autores']) }}</p>
                            @endif

                            @if($book['editora'] && $book['editora'] !== 'Desconhecida')
                                <p><strong>Editora:</strong> {{ $book['editora'] }}</p>
                            @endif

                            @if($book['isbn'])
                                <p><strong>ISBN:</strong> {{ $book['isbn'] }}</p>
                            @endif

                            <p><strong>Preço:</strong>
                                @if($book['preco'] > 0)
                                    {{ number_format($book['preco'], 2, ',', '.') }} €
                                @else
                                    Gratuito
                                @endif
                            </p>
                        </div>

                        @if($book['bibliografia'])
                            <div class="collapse collapse-arrow">
                                <input type="checkbox" class="peer" />
                                <div class="collapse-title text-sm font-medium">
                                    Ver descrição
                                </div>
                                <div class="collapse-content">
                                    <p class="text-sm">{{ Str::limit($book['bibliografia'], 150) }}</p>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('googlebooks.import') }}" method="POST" class="mt-4">
                            @csrf
                            <input type="hidden" name="isbn" value="{{ $book['isbn'] ?? '' }}">
                            <input type="hidden" name="nome" value="{{ $book['nome'] }}">
                            <input type="hidden" name="editora" value="{{ $book['editora'] ?? '' }}">

                            @if(!empty($book['autores']))
                                @foreach($book['autores'] as $autor)
                                    <input type="hidden" name="autores[]" value="{{ $autor }}">
                                @endforeach
                            @else
                                <input type="hidden" name="autores[]" value="">
                            @endif

                            <input type="hidden" name="bibliografia" value="{{ $book['bibliografia'] ?? '' }}">
                            <input type="hidden" name="imagem_capa" value="{{ $book['imagem_capa'] ?? '' }}">
                            <input type="hidden" name="preco" value="{{ $book['preco'] ?? 0 }}">

                            <button type="submit" class="btn btn-success w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                Importar para Biblioteca
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @elseif(isset($query))
        <div class="text-center py-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum livro encontrado</h3>
            <p class="text-gray-500">Tente pesquisar com outros termos ou verifique a sua conexão.</p>
        </div>
    @else
        <div class="text-center py-8">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Pesquise por livros</h3>
            <p class="text-gray-500">Use a barra de pesquisa acima para encontrar livros na Google Books API.</p>
        </div>
    @endif
</div>
@endsection
