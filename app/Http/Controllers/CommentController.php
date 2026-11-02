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

        // Associa o comentário ao usuário autenticado.
        $comment = $post->comments()->make($validated);
        $comment->user()->associate($request->user());
        $comment->save();

        return redirect()
            ->route(
                $this->showRoute($request),
                $post
            )
            ->with('success', 'Comentário criado com sucesso.');
    }

    public function edit(Comment $comment): View
    {
        Gate::authorize('update', $comment);

        // Mantém a tela Blade existente.
        // A edição React usa a rota experimental do pacote.
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
            ->route(
                $this->showRoute($request),
                $comment->post_id
            )
            ->with('success', 'Comentário atualizado com sucesso.');
    }

    public function destroy(
        Request $request,
        Comment $comment
    ): RedirectResponse {
        Gate::authorize('delete', $comment);

        $postId = $comment->post_id;
        $comment->delete();

        return redirect()
            ->route(
                $this->showRoute($request),
                $postId
            )
            ->with('success', 'Comentário excluído com sucesso.');
    }

    /**
     * Escolhe o destino de retorno conforme a interface utilizada.
     * O cabeçalho não concede permissão: a autorização continua nas Policies.
     */
    private function showRoute(Request $request): string
    {
        return $request->header('X-Inertia')
            ? 'react.posts.show.test'
            : 'posts.show';
    }
}
