@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h1 class="mb-4">👥 Lista de Utilizadores</h1>

    <a href="{{ route('users.create') }}" class="btn btn-primary mb-3">+ Novo Administrador</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Email</th>
                <th>Tipo</th>
                <th>Ações</th>
            </tr>
        </thead>

        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span class="badge bg-{{ $user->role_id == 1 ? 'danger' : 'secondary' }}">
                        {{ ucfirst($user->role->name) }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-info">Ver</a>
                </td>
            </tr>
            @endforeach
        </tbody>

    </table>
</div>
@endsection
