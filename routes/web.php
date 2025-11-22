<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LivroController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RequisicaoController;

use App\Livewire\Livros;
use App\Livewire\LivroForm;
use App\Livewire\Autores;
use App\Livewire\AutorForm;
use App\Livewire\Editoras;
use App\Livewire\EditoraForm;



// Rota inicial
Route::get('/', fn() => redirect()->route('dashboard'));

// Verificação de email
Route::get('/email/verify', fn() => view('auth.verify-email'))
    ->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link de verificação enviado!');
})->middleware(['auth','throttle:6,1'])->name('verification.send');


// ÁREA AUTENTICADA
Route::middleware('auth')->group(function () {

    // Catálogo público
Route::get('/catalogo', \App\Livewire\Catalogo::class)->name('catalogo.index');
Route::get('/catalogo/{livro}', \App\Livewire\CatalogoShow::class)->name('catalogo.show');

      // Gestão de requisições (Admin)
        Route::post('/requisicoes/{requisicao}/entregar', [RequisicaoController::class,'marcarEntregue'])
            ->name('requisicoes.entregar');


    // Dashboard
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // Teste email
    Route::get('/test-email', function () {
        Mail::raw('Teste email Laravel', fn($message) => $message->to('andretchipalavela@gmail.com')
            ->subject('📚 Teste de Email - Biblioteca'));
        return 'Email enviado!';
    });

    // Perfil
    Route::view('/user/profile', 'profile.show')->name('profile.show');

    // 2FA
    // Rotas do 2FA - CORRIGIDAS
    Route::post('/user/two-factor-authentication', function (\Illuminate\Http\Request $request) {
        $user = $request->user();

        if (!$user->two_factor_secret) {
            $user->forceFill([
                'two_factor_secret' => encrypt('2fa-secret-' . time()),
                'two_factor_recovery_codes' => encrypt(json_encode([
                    'recovery-code-1',
                    'recovery-code-2',
                    'recovery-code-3'
                ])),
            ])->save();

            session()->flash('message', '2FA ativado com sucesso!');
            session()->flash('alert-type', 'success');
        }

        return redirect()->route('profile.show');
    })->name('two-factor.enable');

    Route::delete('/user/two-factor-authentication', function (\Illuminate\Http\Request $request) {
        $user = $request->user();

        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
        ])->save();

        session()->flash('message', '2FA desativado com sucesso!');
        session()->flash('alert-type', 'success');

        return redirect()->route('profile.show');
    })->name('two-factor.disable');

    // REQUISIÇÕES (Cidadãos + Admin)
    Route::post('/livros/{livro}/requisitar', [RequisicaoController::class, 'store'])->name('requisicoes.store');

    Route::post('/requisicoes/{requisicao}/entregar', [RequisicaoController::class, 'marcarEntregue'])
        ->middleware('can:admin')
        ->name('requisicoes.entregar');

    // USERS (Admin)
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

        // LIVROS (CRUD)
        Route::resource('livros', LivroController::class)->except(['index','show']);
    });

    // REQUISIÇÕES (listar, detalhes)
    Route::resource('requisicoes', RequisicaoController::class)->only(['index','show']);

    // LIVEWIRE
    Route::get('/autores', Autores::class)->name('autores.index');
    Route::get('/autores/create', AutorForm::class)->name('autores.create');
    Route::get('/autores/{autor}/edit', AutorForm::class)->name('autores.edit');

    Route::get('/editoras', Editoras::class)->name('editoras.index');
    Route::get('/editoras/create', EditoraForm::class)->name('editoras.create');
    Route::get('/editoras/{editora}/edit', EditoraForm::class)->name('editoras.edit');

    Route::get('/livros', Livros::class)->name('livros.index');
    Route::get('/livros/create', LivroForm::class)->name('livros.create');
    Route::get('/livros/{livro}/edit', LivroForm::class)->name('livros.edit');
});
