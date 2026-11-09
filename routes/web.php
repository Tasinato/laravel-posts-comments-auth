<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Página inicial
|--------------------------------------------------------------------------
*/
Route::view('/', 'welcome')->name('home');

/*
|--------------------------------------------------------------------------
| Autenticação de usuários
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,1');

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:30,1');

    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->middleware('throttle:5,1')->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->middleware('throttle:10,1')->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Dashboard e logout
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

// Middlewares nativos guest e auth. O primeiro restringe páginas de login e cadastro a visitantes; o segundo exige autenticação. ;
// Login limita falhas por e-mail/IP no controller e o volume total por IP na rota;

/*
|--------------------------------------------------------------------------
| Postagens - Listagem pública
|--------------------------------------------------------------------------
*/
Route::get('/posts', [PostController::class, 'index'])
    ->name('posts.index');

/*
|--------------------------------------------------------------------------
| Postagens - Operações autenticadas
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::resource('posts', PostController::class)
        ->except(['index', 'show']);
});

/*
|--------------------------------------------------------------------------
| Postagens - Visualização pública
|--------------------------------------------------------------------------
*/
Route::get('/posts/{post}', [PostController::class, 'show'])
    ->whereNumber('post')
    ->name('posts.show');

// -------------------------------------------------
// CRUD de Comentários
// -------------------------------------------------

Route::middleware('auth')->group(function () {

    // Criar comentário dentro de uma postagem
    Route::post('/posts/{post}/comments', [
        CommentController::class, 'store',
    ])->name('posts.comments.store');

    // Editar, atualizar e excluir comentários
    Route::resource('comments', CommentController::class)
        ->only(['edit', 'update', 'destroy']);
});
