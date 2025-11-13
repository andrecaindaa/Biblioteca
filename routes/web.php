<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Livros;
use App\Livewire\LivroForm;
use App\Livewire\Autores;
use App\Livewire\AutorForm;
use App\Livewire\Editoras;
use App\Livewire\EditoraForm;
use App\Http\Controllers\LivroController;

// Rota inicial
Route::get('/', fn() => redirect()->route('dashboard'));

// Área autenticada
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

   // Autores
Route::get('/autores', Autores::class)->name('autores.index');
    Route::get('/autores/create', AutorForm::class)->name('autores.create');
    Route::get('/autores/{autor}/edit', AutorForm::class)->name('autores.edit');

// Editoras
Route::get('/editoras', Editoras::class)->name('editoras.index');
    Route::get('/editoras/create', EditoraForm::class)->name('editoras.create');
    Route::get('/editoras/{editora}/edit', EditoraForm::class)->name('editoras.edit');

// Livros
Route::get('/livros', Livros::class)->name('livros.index');
    Route::get('/livros/create', LivroForm::class)->name('livros.create');
    Route::get('/livros/{livro}/edit', LivroForm::class)->name('livros.edit');

});
