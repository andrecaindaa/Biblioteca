<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Perfil - {{ config('app.name', 'Biblioteca') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-base-100">

    <!-- Navigation -->
    <nav class="navbar bg-primary text-primary-content">
        <div class="navbar-start">
            <a href="{{ route('dashboard') }}" class="btn btn-ghost text-xl">Biblioteca</a>
        </div>
        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li><a href="{{ route('admin.livros.index') }}">Livros</a></li>
                <li><a href="{{ route('admin.autores.index') }}">Autores</a></li>
                <li><a href="{{ route('admin.editoras.index') }}">Editoras</a></li>
            </ul>
        </div>
        <div class="navbar-end">
            <div class="dropdown dropdown-end">
                <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                    <div class="w-10 rounded-full bg-base-100">
                        <span class="text-primary font-bold">{{ Auth::user()->initials() }}</span>
                    </div>
                </div>
                <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-50 mt-3 w-52 p-2 shadow text-base-content">
                    <li>
                        <a href="{{ route('profile.show') }}" class="justify-between">Perfil</a>
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left">Sair</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Mensagens de Flash -->
    @if (session()->has('message'))
        <div class="container mx-auto px-4 mt-4">
            <div class="alert alert-{{ session('alert-type', 'info') }} shadow-lg">
                <div>
                    @if(session('alert-type') === 'success')
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    @else
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    @endif
                    <span>{{ session('message') }}</span>
                </div>
            </div>
        </div>
    @endif

    <!-- Page Content -->
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Informações do Perfil -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h3 class="card-title text-2xl mb-6">Informações do Perfil</h3>

                    <div class="flex items-center space-x-6 mb-8">
                        <div class="avatar placeholder">
                            <div class="bg-primary text-primary-content rounded-full w-20">
                                <span class="text-2xl font-bold">{{ Auth::user()->initials() }}</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold">{{ Auth::user()->name }}</h4>
                            <p class="text-base-600 text-lg">{{ Auth::user()->email }}</p>
                            <p class="text-sm text-base-500 mt-2">
                                Membro desde {{ Auth::user()->created_at->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="stats stats-vertical lg:stats-horizontal shadow w-full bg-base-200">
                        <div class="stat">
                            <div class="stat-title text-base-600">Livros Geridos</div>
                            <div class="stat-value text-primary">{{ \App\Models\Livro::count() }}</div>
                        </div>

                        <div class="stat">
                            <div class="stat-title text-base-600">Autores</div>
                            <div class="stat-value text-secondary">{{ \App\Models\Autor::count() }}</div>
                        </div>

                        <div class="stat">
                            <div class="stat-title text-base-600">Editoras</div>
                            <div class="stat-value text-accent">{{ \App\Models\Editora::count() }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configurações de Segurança -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h3 class="card-title text-2xl mb-6">Configurações de Segurança</h3>

                    <div class="space-y-6">
                        <!-- Verificação de Email -->
                        <div class="flex items-center justify-between p-6 bg-base-200 rounded-xl">
                            <div class="flex-1">
                                <h4 class="font-semibold text-lg mb-2">Verificação de Email</h4>
                                <p class="text-base-600">
                                    @if(Auth::user()->hasVerifiedEmail())
                                        <span class="text-success font-semibold">✅ Email verificado</span>
                                    @else
                                        <span class="text-warning font-semibold">⚠️ Email não verificado</span>
                                    @endif
                                </p>
                            </div>
                            @if(!Auth::user()->hasVerifiedEmail())
                                <form method="POST" action="{{ route('verification.send') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline btn-sm lg:btn-md">
                                        Reenviar verificação
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- Autenticação de Dois Fatores -->
                        <!-- Autenticação de Dois Fatores -->
<div class="flex items-center justify-between p-6 bg-base-200 rounded-xl">
    <div class="flex-1">
        <h4 class="font-semibold text-lg mb-2">Autenticação de Dois Fatores (2FA)</h4>
        <p class="text-base-600">
            @if(Auth::user()->two_factor_secret)
                <span class="text-success font-semibold">✅ 2FA Ativo</span>
                <div class="mt-3 p-3 bg-success/10 rounded">
                    <p class="text-sm mb-2 font-semibold">Códigos de Recuperação:</p>
                    <div class="grid grid-cols-2 gap-2">
                        @php
                            try {
                                $recoveryCodes = json_decode(decrypt(Auth::user()->two_factor_recovery_codes), true);
                            } catch (\Exception $e) {
                                $recoveryCodes = ['Código 1', 'Código 2', 'Código 3'];
                            }
                        @endphp
                        @foreach($recoveryCodes as $code)
                            <code class="bg-base-300 px-2 py-1 rounded text-xs font-mono">{{ $code }}</code>
                        @endforeach
                    </div>
                </div>
            @else
                <span class="text-warning font-semibold">⚠️ 2FA Não configurado</span>
                <p class="text-sm mt-1">Proteja sua conta com autenticação de dois fatores.</p>
            @endif
        </p>
    </div>
    @if(!Auth::user()->two_factor_secret)
        <form method="POST" action="{{ route('two-factor.enable') }}">
            @csrf
            <button type="submit" class="btn btn-primary">
                Ativar 2FA
            </button>
        </form>
    @else
        <form method="POST" action="{{ route('two-factor.disable') }}">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-error">
                Desativar 2FA
            </button>
        </form>
    @endif
</div>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h3 class="card-title text-2xl mb-6">Ações Rápidas</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="{{ route('admin.livros.create') }}" class="btn btn-outline btn-primary justify-start">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Adicionar Livro
                        </a>
                        <a href="{{ route('admin.autores.create') }}" class="btn btn-outline btn-secondary justify-start">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Adicionar Autor
                        </a>
                        <a href="{{ route('admin.editoras.create') }}" class="btn btn-outline btn-accent justify-start">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Adicionar Editora
                        </a>
                        <a href="{{ route('admin.livros.index') }}" class="btn btn-outline btn-info justify-start">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Ver Todos os Livros
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @livewireScripts
</body>
</html>
