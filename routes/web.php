<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use App\Models\User;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\RequisicaoController;
use App\Http\Controllers\GoogleBooksController;

use App\Livewire\Livros;
use App\Livewire\LivroForm;
use App\Livewire\Autores;
use App\Livewire\AutorForm;
use App\Livewire\Editoras;
use App\Livewire\EditoraForm;
use App\Livewire\Catalogo;
use App\Livewire\CatalogoShow;

use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;


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

Route::post('/email/verification-notification', function (Request $request) {
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


        //



    });


    // Carrinho
Route::get('/carrinho', [\App\Http\Controllers\CarrinhoController::class, 'index'])->name('carrinho.index');
Route::post('/livros/{livro}/carrinho', [CarrinhoController::class, 'adicionar'])
    ->name('carrinho.adicionar');
Route::put('/carrinho/items/{item}', [\App\Http\Controllers\CarrinhoController::class, 'atualizar'])->name('carrinho.atualizar');
Route::delete('/carrinho/items/{item}', [\App\Http\Controllers\CarrinhoController::class, 'remover'])->name('carrinho.remover');

// Checkout
Route::get('/checkout/address', [\App\Http\Controllers\CheckoutController::class, 'addressForm'])->name('checkout.address');
Route::post('/checkout/address', [\App\Http\Controllers\CheckoutController::class, 'storeAddress'])->name('checkout.address.store');
Route::get('/checkout/payment', [\App\Http\Controllers\CheckoutController::class, 'payment'])->name('checkout.payment');
Route::get('/checkout/success', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/cancel', [\App\Http\Controllers\CheckoutController::class, 'cancel'])->name('checkout.cancel');

// Pedidos
Route::get('/pedidos', [\App\Http\Controllers\PedidoController::class, 'index'])->name('pedidos.index');
Route::get('/pedidos/{pedido}', [\App\Http\Controllers\PedidoController::class, 'show'])->name('pedidos.show');

// Admin Pedidos
Route::get('/admin/pedidos', [\App\Http\Controllers\Admin\PedidoController::class, 'index'])->name('admin.pedidos.index');
Route::get('/admin/pedidos/{pedido}', [\App\Http\Controllers\Admin\PedidoController::class, 'show'])->name('admin.pedidos.show');

// Stripe Webhook (public endpoint)
Route::post('/stripe/webhook', [\App\Http\Controllers\StripeWebhookController::class, 'handle'])->name('stripe.webhook');

    /*
    |--------------------------------------------------------------------------
    | REQUISIÇÕES (Cidadão + Admin)
    |--------------------------------------------------------------------------
    */
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
        ->parameters(['requisicoes' => 'requisicao']);


    /*
    |--------------------------------------------------------------------------
    | REVIEWS — CIDADÃO (apenas após entrega)
    |--------------------------------------------------------------------------
    */
    Route::get('/requisicoes/{requisicao}/review/create', [ReviewController::class, 'create'])
        ->name('reviews.create');

    Route::post('/requisicoes/{requisicao}/review', [ReviewController::class, 'store'])
        ->name('reviews.store');


    /*
    |--------------------------------------------------------------------------
    | GOOGLE BOOKS (apenas Admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth')->group(function () {

        Route::get('/googlebooks/search', function () {
            if (!Auth::user()->isAdmin()) abort(403);
            return app()->call([app(GoogleBooksController::class), 'search']);
        })->name('googlebooks.search');

        Route::post('/googlebooks/import', function (Request $request) {
            if (!Auth::user()->isAdmin()) abort(403);
            return app()->call([app(GoogleBooksController::class), 'import'], ['request' => $request]);
        })->name('googlebooks.import');
    });

});


/*
|--------------------------------------------------------------------------
| REVIEWS — ADMIN (moderação)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth'])->group(function () {

    Route::get('/reviews', [AdminReviewController::class, 'index'])
        ->name('admin.reviews.index');

    Route::get('/reviews/{review}', [AdminReviewController::class, 'show'])
        ->name('admin.reviews.show');

    Route::post('/reviews/{review}/approve', [AdminReviewController::class, 'approve'])
        ->name('admin.reviews.approve');

    Route::post('/reviews/{review}/reject', [AdminReviewController::class, 'reject'])
        ->name('admin.reviews.reject');
});


/*
|--------------------------------------------------------------------------
| ÁREA ADMINISTRATIVA — Users / Livros / Autores / Editoras
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {

    // Dashboard Admin
    Route::get('/dashboard', function () {
        if (!Auth::user()->isAdmin()) abort(403);
        return view('admin.dashboard');
    })->name('admin.dashboard');


    /*
    |--------------------------------------------------------------------------
    | Gestão de usuários
    |--------------------------------------------------------------------------
    */
    Route::get('/users', fn() => Auth::user()->isAdmin() ? (new UserController)->index() : abort(403))
        ->name('users.index');

    Route::get('/users/create', fn() => Auth::user()->isAdmin() ? (new UserController)->create() : abort(403))
        ->name('users.create');

    Route::post('/users', fn(Request $request) => Auth::user()->isAdmin() ? (new UserController)->store($request) : abort(403))
        ->name('users.store');

    Route::get('/users/{user}', fn(User $user) => Auth::user()->isAdmin() ? (new UserController)->show($user) : abort(403))
        ->name('users.show');

    Route::get('/users/{user}/edit', fn(User $user) => Auth::user()->isAdmin() ? (new UserController)->edit($user) : abort(403))
        ->name('users.edit');

    Route::put('/users/{user}', fn(Request $request, User $user) => Auth::user()->isAdmin() ? (new UserController)->update($request, $user) : abort(403))
        ->name('users.update');

    Route::delete('/users/{user}', fn(User $user) => Auth::user()->isAdmin() ? (new UserController)->destroy($user) : abort(403))
        ->name('users.destroy');


    /*
    |--------------------------------------------------------------------------
    | Livros
    |--------------------------------------------------------------------------
    */
    Route::get('/livros', fn() => Auth::user()->isAdmin() ? app()->call(Livros::class) : abort(403))
        ->name('admin.livros.index');

    Route::get('/livros/create', fn() => Auth::user()->isAdmin() ? app()->call(LivroForm::class) : abort(403))
        ->name('admin.livros.create');

    Route::get('/livros/{livro}/edit', fn($livro) => Auth::user()->isAdmin() ? app()->call(LivroForm::class, ['livro' => $livro]) : abort(403))
        ->name('admin.livros.edit');


    /*
    |--------------------------------------------------------------------------
    | Autores
    |--------------------------------------------------------------------------
    */
    Route::get('/autores', fn() => Auth::user()->isAdmin() ? app()->call(Autores::class) : abort(403))
        ->name('admin.autores.index');

    Route::get('/autores/create', fn() => Auth::user()->isAdmin() ? app()->call(AutorForm::class) : abort(403))
        ->name('admin.autores.create');

    Route::get('/autores/{autor}/edit', fn($autor) => Auth::user()->isAdmin() ? app()->call(AutorForm::class, ['autor' => $autor]) : abort(403))
        ->name('admin.autores.edit');


    /*
    |--------------------------------------------------------------------------
    | Editoras
    |--------------------------------------------------------------------------
    */
    Route::get('/editoras', fn() => Auth::user()->isAdmin() ? app()->call(Editoras::class) : abort(403))
        ->name('admin.editoras.index');

    Route::get('/editoras/create', fn() => Auth::user()->isAdmin() ? app()->call(EditoraForm::class) : abort(403))
        ->name('admin.editoras.create');

    Route::get('/editoras/{editora}/edit', fn($editora) => Auth::user()->isAdmin() ? app()->call(EditoraForm::class, ['editora' => $editora]) : abort(403))
        ->name('admin.editoras.edit');

        Route::view('/two-factor-challenge', 'auth.two-factor-challenge')
    ->name('two-factor.login');


});
