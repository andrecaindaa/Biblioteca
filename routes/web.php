<?php


use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\User;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RequisicaoController;

use App\Livewire\Livros;
use App\Livewire\LivroForm;
use App\Livewire\Autores;
use App\Livewire\AutorForm;
use App\Livewire\Editoras;
use App\Livewire\EditoraForm;
use App\Livewire\Catalogo;
use App\Livewire\CatalogoShow;

/*
|--------------------------------------------------------------------------
| ROTA RAIZ
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('dashboard'));

/*
|--------------------------------------------------------------------------
| VERIFICAÇÃO DE EMAIL
|--------------------------------------------------------------------------
*/
Route::get('/email/verify', fn() => view('auth.verify-email'))
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link de verificação enviado!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

/*
|--------------------------------------------------------------------------
| ÁREA AUTENTICADA
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // Perfil
    Route::view('/user/profile', 'profile.show')->name('profile.show');

    // Catálogo público
    Route::get('/catalogo', Catalogo::class)->name('catalogo.index');
    Route::get('/catalogo/{livro}', CatalogoShow::class)->name('catalogo.show');

    // Teste de email
    Route::get('/test-email', function () {
        Mail::raw('Teste email Laravel', fn($msg) =>
            $msg->to('admin@biblioteca.test')->subject('📚 Teste de Email - Biblioteca')
        );
        return 'Email enviado!';
    });

    /*
    |--------------------------------------------------------------------------
    | REQUISIÇÕES (Cidadão + Admin)
    |--------------------------------------------------------------------------
    */
    /*Route::get('/livros/{livro}/requisitar', [RequisicaoController::class, 'create'])
        ->name('users.requisitar.form');

    Route::post('/livros/{livro}/requisitar', [RequisicaoController::class, 'store'])
        ->name('users.requisitar.store');*/

        Route::get('/livros/{livro}/requisitar', [UserController::class, 'requisitarForm'])
    ->name('users.requisitar.form');

Route::post('/livros/{livro}/requisitar', [UserController::class, 'requisitar'])
    ->name('users.requisitar.store');

    Route::get('/requisicoes/{requisicao}/confirmar', [RequisicaoController::class, 'confirmar'])
        ->name('requisicoes.confirmar');

    Route::post('/requisicoes/{requisicao}/confirmar', [RequisicaoController::class, 'confirmarStore'])
        ->name('requisicoes.confirmar.store');

    Route::post('/requisicoes/{requisicao}/entregar', [RequisicaoController::class, 'marcarEntregue'])
        ->name('requisicoes.entregar');

    Route::resource('requisicoes', RequisicaoController::class)
    ->only(['index', 'show'])
    ->parameters([
        'requisicoes' => 'requisicao'
    ]);

    /*
    |--------------------------------------------------------------------------
    | ÁREA ADMINISTRATIVA (Livewire sem middleware)
    |--------------------------------------------------------------------------
    */

// Dashboard Admin
Route::get('/admin/dashboard', function () {
    if (!Auth::user()->isAdmin()) abort(403);
    return view('admin.dashboard');
})->name('admin.dashboard');

// Gestão de usuários
Route::prefix('admin')->group(function () {

    Route::get('/users', function () {
        if (!Auth::user()->isAdmin()) abort(403);
        return (new UserController)->index();
    })->name('users.index');

    Route::get('/users/create', function () {
        if (!Auth::user()->isAdmin()) abort(403);
        return (new UserController)->create();
    })->name('users.create');

    Route::post('/users', function (Request $request) {
        if (!Auth::user()->isAdmin()) abort(403);
        return (new UserController)->store($request);
    })->name('users.store');

    Route::get('/users/{user}', function (User $user) {
        if (!Auth::user()->isAdmin()) abort(403);
        return (new UserController)->show($user);
    })->name('users.show');

    Route::get('/users/{user}/edit', function (User $user) {
        if (!Auth::user()->isAdmin()) abort(403);
        return (new UserController)->edit($user);
    })->name('users.edit');

    Route::put('/users/{user}', function (Request $request, User $user) {
        if (!Auth::user()->isAdmin()) abort(403);
        return (new UserController)->update($request, $user);
    })->name('users.update');

    Route::delete('/users/{user}', function (User $user) {
        if (!Auth::user()->isAdmin()) abort(403);
        return (new UserController)->destroy($user);
    })->name('users.destroy');
});

// Livros
Route::get('/admin/livros', function () {
    if (!Auth::user()->isAdmin()) abort(403);
    return app()->call(Livros::class);
})->name('admin.livros.index');

Route::get('/admin/livros/create', function () {
    if (!Auth::user()->isAdmin()) abort(403);
    return app()->call(LivroForm::class);
})->name('admin.livros.create');

Route::get('/admin/livros/{livro}/edit', function ($livro) {
    if (!Auth::user()->isAdmin()) abort(403);
    return app()->call(LivroForm::class, ['livro' => $livro]);
})->name('admin.livros.edit');

// Autores
Route::get('/admin/autores', function () {
    if (!Auth::user()->isAdmin()) abort(403);
    return app()->call(Autores::class);
})->name('admin.autores.index');

Route::get('/admin/autores/create', function () {
    if (!Auth::user()->isAdmin()) abort(403);
    return app()->call(AutorForm::class);
})->name('admin.autores.create');

Route::get('/admin/autores/{autor}/edit', function ($autor) {
    if (!Auth::user()->isAdmin()) abort(403);
    return app()->call(AutorForm::class, ['autor' => $autor]);
})->name('admin.autores.edit');

// Editoras
Route::get('/admin/editoras', function () {
    if (!Auth::user()->isAdmin()) abort(403);
    return app()->call(Editoras::class);
})->name('admin.editoras.index');

Route::get('/admin/editoras/create', function () {
    if (!Auth::user()->isAdmin()) abort(403);
    return app()->call(EditoraForm::class);
})->name('admin.editoras.create');

Route::get('/admin/editoras/{editora}/edit', function ($editora) {
    if (!Auth::user()->isAdmin()) abort(403);
    return app()->call(EditoraForm::class, ['editora' => $editora]);
})->name('admin.editoras.edit');

});
