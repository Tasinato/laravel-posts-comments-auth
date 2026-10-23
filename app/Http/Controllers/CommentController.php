<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function store(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string'],
        ]);

        $comment = $post->comments()->make($validated);

        $comment->user()->associate($request->user());

        $comment->save();

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'Comentário criado com sucesso.');
    }

    public function edit(Comment $comment): View
    {
        Gate::authorize('update', $comment);


        return view('comments.edit', compact('comment'));
    }

    public function update(
        Request $request,
        Comment $comment
    ): RedirectResponse {
        Gate::authorize('update', $comment);

        $validated = $request->validate([
            'content' => ['required', 'string'],
        ]);

        $comment->update($validated);

        return redirect()
            ->route('posts.show', $comment->post_id)
            ->with('success', 'Comentário atualizado com sucesso.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        Gate::authorize('delete', $comment);

        $postId = $comment->post_id;

        $comment->delete();

        return redirect()
            ->route('posts.show', $postId)
            ->with('success', 'Comentário excluído com sucesso.');
    }
}

/* O método store() recebe a postagem e o conteúdo do comentário. O usuário autenticado é associado automaticamente ao comentário, sem confiar em um user_id enviado pelo formulário.

Os métodos edit(), update() e destroy() verificam a autoria antes de realizar a operação. Na etapa 6, essas verificações serão substituídas por chamadas às Laravel Policies.
*/