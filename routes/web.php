<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Livros;
use App\Http\Livewire\LivroForm;
use App\Http\Livewire\Autores;
use App\Http\Livewire\AutorForm;
use App\Http\Livewire\Editoras;
use App\Http\Livewire\EditoraForm;
use App\Http\Controllers\LivroController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rota inicial: redireciona para dashboard
Route::get('/', fn() => redirect()->route('dashboard'));

// Área autenticada
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // Livros
    Route::get('/livros', Livros::class)->name('livros.index');
    Route::get('/livros/create', \App\Http\Livewire\LivroForm::class)->name('livros.create');
    Route::get('/livros/{livro}/edit', \App\Http\Livewire\LivroForm::class)->name('livros.edit');

    Route::delete('/livros/{livro}', [LivroController::class, 'destroy'])->name('livros.destroy');

    // Autores
    Route::get('/autores', Autores::class)->name('autores.index');
    Route::get('/autores/create', \App\Http\Livewire\AutorForm::class)->name('autores.create');
   Route::get('/autores/{autor}/edit', \App\Http\Livewire\AutorForm::class)->name('autores.edit');

    // Editoras
    Route::get('/editoras', Editoras::class)->name('editoras.index');
    Route::get('/editoras/create', \App\Http\Livewire\EditoraForm::class)->name('editoras.create');
    Route::get('/editoras/{editora}/edit', \App\Http\Livewire\EditoraForm::class)->name('editoras.edit');


});



