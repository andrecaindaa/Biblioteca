<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">Biblioteca</a>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('livros.index') }}">Livros</a>
                </li>

                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('users.show', auth()->id()) }}">Meu Perfil</a>
                    </li>

                    @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.dashboard') }}">Admin</a>
                        </li>
                    @endif
                @endauth

            </ul>

            <ul class="navbar-nav">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Entrar</a>
                    </li>
                @else
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="btn btn-link nav-link">Sair</button>
                        </form>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>
