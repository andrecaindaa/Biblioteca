@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-7xl p-6">

    <h1 class="text-2xl font-bold mb-6">🧾 Logs do Sistema</h1>

    <div class="card bg-base-200 shadow-md">
        <div class="card-body p-0 overflow-x-auto">

            <table class="table table-zebra w-full">
                <thead class="bg-base-300">
                    <tr>
                        <th>Data</th>
                        <th>Utilizador</th>
                        <th>Módulo</th>
                        <th>Ação</th>
                        <th>Objeto</th>
                        <th>IP</th>
                        <th>Browser</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td class="whitespace-nowrap">
                                {{ $log->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td>
                                {{ $log->user->name ?? 'Sistema' }}
                            </td>

                            <td>
                                <span class="badge badge-outline">
                                    {{ $log->modulo }}
                                </span>
                            </td>

                            <td>
                                {{ $log->acao }}
                            </td>

                            <td>
                                {{ $log->objeto_id ?? '—' }}
                            </td>

                            <td class="font-mono text-sm">
                                {{ $log->ip }}
                            </td>

                            <td class="text-sm text-gray-500">
                                {{ \Illuminate\Support\Str::limit($log->browser, 40) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 py-6">
                                Nenhum log registado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

    {{-- Paginação --}}
    <div class="mt-6">
        {{ $logs->links() }}
    </div>

</div>
@endsection
