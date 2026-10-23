<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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

    public function show(Post $post): View
    {
        $post->load('user');

        return view('posts.show', compact('post'));
    }

    public function edit(Post $post): View
    {
        abort_unless(Auth::id() === $post->user_id, 403);

        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        abort_unless(Auth::id() === $post->user_id, 403);

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
 * Exclui uma postagem somente se o usuário
 * autenticado for seu proprietário.
 *
 * Na etapa 6, a autorização será
 * centralizada na PostPolicy.
 */

    public function destroy(Post $post): RedirectResponse
    {
        abort_unless(Auth::id() === $post->user_id, 403);

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