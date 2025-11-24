@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestão de Utilizadores</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            + Novo Utilizador
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Papel</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $u)
            <tr>
                <td>{{ $u->name }}</td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->role->nome ?? 'Cidadão' }}</td>
                <td>
                    <a href="{{ route('users.show', $u->id) }}" class="btn btn-sm btn-info">Ver</a>
                    <a href="{{ route('users.edit', $u->id) }}" class="btn btn-sm btn-primary">Editar</a>

                    <form action="{{ route('users.destroy', $u->id) }}"
                          method="POST"
                          class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Eliminar utilizador?')">
                            Eliminar
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</div>
@endsection
