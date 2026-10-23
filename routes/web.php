<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;

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

    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1');
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


//Middlewares nativos guest e auth. O primeiro restringe páginas de login e cadastro a visitantes; o segundo exige autenticação. ;
//Também limitamos as tentativas de login a cinco por minuto na rota, como proteção básica contra tentativas repetidas;




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