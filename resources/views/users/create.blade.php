@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h1 class="mb-4">➕ Criar Administrador</h1>

    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nome</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Palavra-passe</label>
            <input type="password" name="password" class="form-control" required minlength="6">
        </div>

        <button type="submit" class="btn btn-primary">Criar</button>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>

    </form>

</div>
@endsection
