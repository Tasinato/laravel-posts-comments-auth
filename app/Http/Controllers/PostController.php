<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;


class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::with('user')
            ->latest()
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function create(): View
    {
        return view('posts.create');
    }

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
            ->route('posts.show', $post)
            ->with('success', 'Postagem criada com sucesso.');
    }

    //Laravel carrega o autor da postagem "user", seus comentários "comments" e o autor de cada comentário.

    public function show(Post $post): View
    {
        $post->load(['user', 'comments.user',]);

        return view('posts.show', compact('post'));
    }

    public function edit(Post $post): View
    {
        Gate::authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);

        $post->update($validated);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Postagem atualizada com sucesso.');
    }


    /**
 * Gate::authorize('delete', $post);
 * consulta a Policy correspondente e lança uma exceção de autorização 
 * caso a ação seja negada, 
 * que o Laravel converte em HTTP 403.
 */

    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Postagem excluída com sucesso.');
    }
}

/*Para preservar a regra de negócio desde o início,
* vamos verificar se o usuário autenticado é
* o autor antes de permitir edição ou exclusão.
* Na etapa 6, essas verificações serão transferidas para as Policies.
*/


/* Nesse controller, index() e show() 
* permitem leitura pública; store()
* associa o post ao usuário autenticado; 
* e os métodos de alteração verificam a autoria.
*/

