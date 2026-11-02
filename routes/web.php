<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Página inicial
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome')->name('home');

/*
|--------------------------------------------------------------------------
| Autenticação
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
| Painel e logout
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');
});

/*
|--------------------------------------------------------------------------
| Postagens Blade
|--------------------------------------------------------------------------
*/

// Listagem pública.
Route::get('/posts', [PostController::class, 'index'])
    ->name('posts.index');

// Criação, edição, atualização e exclusão exigem autenticação.
// Os controllers verificam a autoria pelas Policies.
Route::middleware('auth')->group(function () {
    Route::resource('posts', PostController::class)
        ->except(['index', 'show']);
});

// Leitura pública de uma postagem.
Route::get('/posts/{post}', [PostController::class, 'show'])
    ->whereNumber('post')
    ->name('posts.show');

/*
|--------------------------------------------------------------------------
| Operações de comentários
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::post('/posts/{post}/comments', [
        CommentController::class,
        'store',
    ])->name('posts.comments.store');

    Route::resource('comments', CommentController::class)
        ->only(['edit', 'update', 'destroy']);
});

/*
|--------------------------------------------------------------------------
| Teste inicial React + Inertia
|--------------------------------------------------------------------------
*/

Route::get('/react-test', function () {
    return Inertia::render('ReactTest');
})->name('react.test');

/*
|--------------------------------------------------------------------------
| Listagem React
|--------------------------------------------------------------------------
*/

Route::get('/react-posts-test', function () {
    return Inertia::render('Posts/Index', [
        'createUrl' => route('react.posts.create.test'),

        'posts' => \App\Models\Post::query()
            ->with('user:id,name')
            ->latest()
            ->paginate(10)
            ->through(fn ($post) => [
                'id' => $post->id,
                'title' => $post->title,
                'content' => $post->content,
                'author' => $post->user->name,
                'created_at' => $post->created_at?->format('d/m/Y'),
                'url' => route('react.posts.show.test', $post),
            ]),
    ]);
})->name('react.posts.test');

/*
|--------------------------------------------------------------------------
| Visualização React de postagem e comentários
|--------------------------------------------------------------------------
*/

Route::get('/react-posts-test/{post}', function (\App\Models\Post $post) {
    $post->load(['user', 'comments.user']);

    return Inertia::render('Posts/Show', [
        'post' => [
            'id' => $post->id,
            'title' => $post->title,
            'content' => $post->content,
            'author' => $post->user->name,

            // Permissões calculadas no servidor pelas Policies.
            'can' => [
                'update' => \Illuminate\Support\Facades\Gate::allows(
                    'update',
                    $post
                ),
                'delete' => \Illuminate\Support\Facades\Gate::allows(
                    'delete',
                    $post
                ),
            ],

            'urls' => [
                'edit' => route('react.posts.edit.test', $post),
                'destroy' => route('posts.destroy', $post),
            ],

            'comments' => $post->comments->map(fn ($comment) => [
                'id' => $comment->id,
                'content' => $comment->content,
                'author' => $comment->user->name,

                'can' => [
                    'update' => \Illuminate\Support\Facades\Gate::allows(
                        'update',
                        $comment
                    ),
                    'delete' => \Illuminate\Support\Facades\Gate::allows(
                        'delete',
                        $comment
                    ),
                ],

                'urls' => [
                    'edit' => route('react.comments.edit.test', $comment),
                    'destroy' => route('comments.destroy', $comment),
                ],
            ])->values()->all(),
        ],

        'urls' => [
            'index' => route('react.posts.test'),
            'login' => route('login'),
            'commentStore' => route('posts.comments.store', $post),
        ],

        'csrfToken' => csrf_token(),
    ]);
})->whereNumber('post')->name('react.posts.show.test');

/*
|--------------------------------------------------------------------------
| Formulários React autenticados
|--------------------------------------------------------------------------
|
| As operações de escrita usam os controllers existentes.
| As telas de edição também verificam as Policies.
|
*/

Route::middleware('auth')->group(function () {
    // Nova postagem em React.
    Route::get('/react-posts-test/create', function () {
        return Inertia::render('Posts/Create', [
            'urls' => [
                'submit' => route('posts.store'),
                'cancel' => route('react.posts.test'),
            ],
        ]);
    })->name('react.posts.create.test');

    // Edição de postagem em React.
    Route::get(
        '/react-posts-test/{post}/edit',
        function (\App\Models\Post $post) {
            \Illuminate\Support\Facades\Gate::authorize('update', $post);

            return Inertia::render('Posts/Edit', [
                'post' => $post->only(['id', 'title', 'content']),

                'urls' => [
                    'submit' => route('posts.update', $post),
                    'cancel' => route('react.posts.show.test', $post),
                ],
            ]);
        }
    )->whereNumber('post')->name('react.posts.edit.test');

    // Edição de comentário em React.
    Route::get(
        '/react-comments-test/{comment}/edit',
        function (\App\Models\Comment $comment) {
            \Illuminate\Support\Facades\Gate::authorize(
                'update',
                $comment
            );

            return Inertia::render('Comments/Edit', [
                'comment' => $comment->only(['id', 'content']),

                'urls' => [
                    'submit' => route('comments.update', $comment),
                    'cancel' => route(
                        'react.posts.show.test',
                        $comment->post_id
                    ),
                ],
            ]);
        }
    )->whereNumber('comment')->name('react.comments.edit.test');
});
