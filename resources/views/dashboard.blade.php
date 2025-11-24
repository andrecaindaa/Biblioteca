<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-base-800 leading-tight">
            Dashboard - Sistema de Gestão de Biblioteca
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total de Livros -->
                <div class="card bg-primary text-primary-content shadow-xl">
                    <div class="card-body">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="card-title text-3xl font-bold">
                                    {{ \App\Models\Livro::count() }}
                                </h3>
                                <p class="text-primary-content/80">Total de Livros</p>
                            </div>
                            <div class="text-4xl">
                                📚
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total de Autores -->
                <div class="card bg-secondary text-secondary-content shadow-xl">
                    <div class="card-body">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="card-title text-3xl font-bold">
                                    {{ \App\Models\Autor::count() }}
                                </h3>
                                <p class="text-secondary-content/80">Autores Registados</p>
                            </div>
                            <div class="text-4xl">
                                👨‍🎓
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total de Editoras -->
                <div class="card bg-accent text-accent-content shadow-xl">
                    <div class="card-body">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="card-title text-3xl font-bold">
                                    {{ \App\Models\Editora::count() }}
                                </h3>
                                <p class="text-accent-content/80">Editoras Parceiras</p>
                            </div>
                            <div class="text-4xl">
                                🏢
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ações Rápidas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Card de Ações -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title text-2xl mb-4">
                            <svg class="w-8 h-8 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            Ações Rápidas
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <a href="{{ route('admin.livros.index') }}" class="btn btn-outline btn-primary justify-start">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                Gerir Livros
                            </a>
                            <a href="{{ route('admin.autores.index') }}" class="btn btn-outline btn-secondary justify-start">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Gerir Autores
                            </a>
                            <a href="{{ route('admin.editoras.index') }}" class="btn btn-outline btn-accent justify-start">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Gerir Editoras
                            </a>
                            <a href="{{ route('admin.livros.index') }}" class="btn btn-outline btn-success justify-start">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Exportar Dados
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Últimos Livros Adicionados -->
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <h2 class="card-title text-2xl mb-4">
                            <svg class="w-8 h-8 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Últimos Livros
                        </h2>
                        <div class="space-y-4">
                            @php
                                $ultimosLivros = \App\Models\Livro::with('editora')->latest()->take(5)->get();
                            @endphp

                            @forelse($ultimosLivros as $livro)
                                <div class="flex items-center space-x-3 p-3 bg-base-200 rounded-lg">
                                    @if($livro->imagem_capa)
                                        <div class="avatar">
                                            <div class="w-12 h-12 rounded">
                                                <img src="{{ asset('storage/' . $livro->imagem_capa) }}" alt="{{ $livro->nome }}">
                                            </div>
                                        </div>
                                    @else
                                        <div class="avatar placeholder">
                                            <div class="bg-neutral text-neutral-content rounded w-12">
                                                <span class="text-xs">📖</span>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold truncate">{{ $livro->nome }}</p>
                                        <p class="text-sm text-base-600">{{ $livro->editora->nome ?? 'Sem editora' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-mono text-sm">
                                            @php
                                                $preco = floatval($livro->preco ?? 0);
                                            @endphp
                                            {{ number_format($preco, 2, ',', '.') }} €
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-base-500">
                                    <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    <p>Nenhum livro registado ainda</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informações do Sistema -->
            <div class="card bg-base-100 shadow-xl">
                <div class="card-body">
                    <h2 class="card-title text-2xl mb-4">
                        <svg class="w-8 h-8 text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Informações do Sistema
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="stat">
                                <div class="stat-title">Versão Laravel</div>
                                <div class="stat-value text-lg">{{ app()->version() }}</div>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="stat">
                                <div class="stat-title">Ambiente</div>
                                <div class="stat-value text-lg">{{ app()->environment() }}</div>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="stat">
                                <div class="stat-title">2FA</div>
                                <div class="stat-value text-lg text-success">Ativo</div>
                            </div>
                        </div>
                        <div class="text-center">
                            <div class="stat">
                                <div class="stat-title">Dados</div>
                                <div class="stat-value text-lg text-info">Cifrados</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
