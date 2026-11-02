<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Listagem Blade com paginação.
     */
    public function index(): View
    {
        $posts = Post::with('user')
            ->latest()
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    /**
     * Formulário Blade de nova postagem.
     * O formulário React utiliza a rota experimental.
     */
    public function create(): View
    {
        return view('posts.create');
    }

    /**
     * Valida e cria uma postagem para o usuário autenticado.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $post = $request->user()
            ->posts()
            ->create($validated);

        return redirect()
            ->route($this->showRoute($request), $post)
            ->with('success', 'Postagem criada com sucesso.');
    }

    /**
     * Exibe a postagem em Blade com autor e comentários.
     */
    public function show(Post $post): View
    {
        $post->load(['user', 'comments.user']);

        return view('posts.show', compact('post'));
    }

    /**
     * Autoriza e abre a edição Blade.
     * A edição React utiliza a rota experimental.
     */
    public function edit(Post $post): View
    {
        Gate::authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    /**
     * Autoriza, valida e atualiza a postagem.
     */
    public function update(
        Request $request,
        Post $post
    ): RedirectResponse {
        Gate::authorize('update', $post);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $post->update($validated);

        return redirect()
            ->route($this->showRoute($request), $post)
            ->with('success', 'Postagem atualizada com sucesso.');
    }

    /**
     * Exclui somente após autorização pela Policy.
     */
    public function destroy(
        Request $request,
        Post $post
    ): RedirectResponse {
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()
            ->route(
                $request->header('X-Inertia')
                    ? 'react.posts.test'
                    : 'posts.index'
            )
            ->with('success', 'Postagem excluída com sucesso.');
    }

    /**
     * Escolhe a página de retorno conforme a interface.
     * A autorização permanece nas Policies.
     */
    private function showRoute(Request $request): string
    {
        return $request->header('X-Inertia')
            ? 'react.posts.show.test'
            : 'posts.show';
    }
}
