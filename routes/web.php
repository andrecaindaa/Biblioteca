<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Livewire\Livros;
use App\Livewire\LivroForm;
use App\Livewire\Autores;
use App\Livewire\AutorForm;
use App\Livewire\Editoras;
use App\Livewire\EditoraForm;

// Rota inicial
Route::get('/', fn() => redirect()->route('dashboard'));

// Rotas de verificação de email
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (\Illuminate\Http\Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link de verificação enviado!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Área autenticada
Route::middleware(['auth'])->group(function () {


    // TESTE DE EMAIL
    Route::get('/test-email', function () {
        Mail::raw('Este é um e-mail de teste enviado pelo Laravel usando Mailtrap.', function ($message) {
            $message->to('andretchipalavela@gmail.com')
                    ->subject('📚 Teste de Email - Biblioteca');
        });

        return 'Email enviado! Verifica a tua caixa de entrada.';
    });

    // Dashboard
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // Perfil do Utilizador
    Route::view('/user/profile', 'profile.show')->name('profile.show');

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


    // Requisições
Route::post('/livros/{livro}/requisitar', [\App\Http\Controllers\RequisicaoController::class, 'store'])
    ->name('requisicoes.store');

Route::post('/requisicoes/{requisicao}/entregar', [\App\Http\Controllers\RequisicaoController::class, 'marcarEntregue'])
    ->middleware('can:admin') // quando criarmos roles
    ->name('requisicoes.entregar');


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
