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
Route::get('/autores/create', fn() => view('autores.create'))->name('autores.create');
Route::get('/autores/{autor}/edit', fn(App\Models\Autor $autor) => view('autores.edit', compact('autor')))->name('autores.edit');

// Editoras
Route::get('/editoras', Editoras::class)->name('editoras.index');
Route::get('/editoras/create', \App\Livewire\EditoraForm::class)->name('editoras.create');

Route::get('/editoras/{editora}/edit', fn(App\Models\Editora $editora) => view('editoras.edit', compact('editora')))->name('editoras.edit');

// Livros
Route::get('/livros', Livros::class)->name('livros.index');
Route::get('/livros/create', fn() => view('livros.create'))->name('livros.create');
Route::get('/livros/{livro}/edit', fn(App\Models\Livro $livro) => view('livros.edit', compact('livro')))->name('livros.edit');

});
