@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Criar Novo Utilizador</h4>
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">
                            ← Voltar
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nome Completo</label>
                            <input type="text" name="name" id="name" class="form-control form-control-lg"
                                   value="{{ old('name') }}" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control form-control-lg"
                                   value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" id="password" class="form-control form-control-lg"
                                   required minlength="6">
                            <div class="form-text">A password deve ter pelo menos 6 caracteres.</div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('users.index') }}" class="btn btn-lg btn-secondary me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-lg btn-primary">
                                <i class="fas fa-user-plus"></i> Criar Utilizador
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    border-radius: 1rem;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 1rem 1rem 0 0 !important;
    padding: 1.5rem 2rem;
}

.form-control-lg {
    border-radius: 0.5rem;
    border: 2px solid #e9ecef;
    transition: all 0.3s;
}

.form-control-lg:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-lg {
    border-radius: 0.5rem;
    padding: 0.75rem 1.5rem;
    font-weight: 600;
}
</style>
@endsection
