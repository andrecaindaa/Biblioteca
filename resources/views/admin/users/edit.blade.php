@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Editar Utilizador: {{ $user->name }}</h1>

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="name" class="form-control"
                   value="{{ $user->name }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                   value="{{ $user->email }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Papel</label>
            <select name="role_id" class="form-control">
                @foreach($roles as $role)
                    <option value="{{ $role->id }}"
                        {{ $user->role_id == $role->id ? 'selected' : '' }}>
                        {{ $role->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary">Guardar Alterações</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>

</div>
@endsection
