<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-base-200 min-h-screen">

<div class="navbar bg-primary text-primary-content">
    <div class="flex-1 px-2 text-xl font-bold">📚 Biblioteca</div>
    <div class="flex-none">
        <ul class="menu menu-horizontal px-1">
            <li><a href="{{ route('dashboard') }}">Início</a></li>
            <li><a href="{{ route('catalogo.index') }}">Catálogo</a></li>
            <li><a href="{{ route('requisicoes.index') }}">Requisições</a></li>

            @auth
                @if(auth()->user()->isAdmin())
                    @if(Route::has('livros.index'))
                        <li><a href="{{ route('livros.index') }}">Livros</a></li>
                    @endif
                    @if(Route::has('autores.index'))
                        <li><a href="{{ route('autores.index') }}">Autores</a></li>
                    @endif
                    @if(Route::has('editoras.index'))
                        <li><a href="{{ route('editoras.index') }}">Editoras</a></li>
                    @endif
                    @if(Route::has('users.index'))
                        <li><a href="{{ route('users.index') }}">Utilizadores</a></li>
                    @endif

                    @if(Route::has('admin.reviews.index'))
                        <li><a href="{{ route('admin.reviews.index') }}">Reviews</a></li>
                    @endif


    <li>
        <a href="{{ route('admin.pedidos.index') }}" class="nav-link">
            📦 Encomendas
        </a>
    </li>

                @endif


                @auth
                    @if(!auth()->user()->isAdmin())
                        @php
                            $carrinho = \App\Models\Carrinho::firstOrCreate(['user_id' => auth()->id()]);
                            $qtdItens = $carrinho->items->sum('quantidade');
                        @endphp

                        <li>
                            <a href="{{ route('carrinho.index') }}" class="relative">
                                🛒 Carrinho
                                @if($qtdItens > 0)
                                    <span class="badge badge-sm bg-secondary text-white absolute -top-2 -right-2">
                                        {{ $qtdItens }}
                                    </span>
                                @endif
                            </a>
                            <a href="{{ route('pedidos.index') }}" class="relative">
                                Minhas Encomendas
                                @if($qtdItens > 0)
                                    <span class="badge badge-sm bg-secondary text-white absolute -top-2 -right-2">
                                        {{ $qtdItens }}
                                    </span>
                                @endif
                            </a>
                        </li>
                    @endif
                @endauth


                <li><a href="{{ route('profile.show') }}">Perfil</a></li>
                <li><a href="{{ route('admin.logs.index') }}">Logs</a></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-ghost">Sair</button>
                    </form>
                </li>
            @else
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('register') }}">Registo</a></li>
            @endauth
        </ul>
    </div>
</div>


    <div class="p-6">
        @yield('content')
    </div>

</body>
</html>
