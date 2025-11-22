@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Gestão de Utilizadores</h1>

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
                <td>{{ $u->role->name ?? 'Cidadão' }}</td>
                <td>
                    <a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-sm btn-primary">Editar</a>

                    <form action="{{ route('admin.users.destroy', $u->id) }}"
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
