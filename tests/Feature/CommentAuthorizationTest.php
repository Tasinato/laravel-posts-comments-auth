<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_outro_usuario_nao_pode_atualizar_comentario(): void
    {
        $autor = User::factory()->create();
        $outroUsuario = User::factory()->create();

        $post = $autor->posts()->create([
            'title' => 'Post de teste',
            'content' => 'Conteúdo do post',
        ]);

        $comment = $post->comments()->make([
            'content' => 'Comentário original',
        ]);

        $comment->user()->associate($autor);
        $comment->save();

        $response = $this->actingAs($outroUsuario)
            ->put(route('comments.update', $comment), [
                'content' => 'Alteração indevida',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => 'Comentário original',
        ]);
    }

    public function test_outro_usuario_nao_pode_excluir_comentario(): void
    {
        $autor = User::factory()->create();
        $outroUsuario = User::factory()->create();

        $post = $autor->posts()->create([
            'title' => 'Post de teste',
            'content' => 'Conteúdo do post',
        ]);

        $comment = $post->comments()->make([
            'content' => 'Comentário protegido',
        ]);

        $comment->user()->associate($autor);
        $comment->save();

        $response = $this->actingAs($outroUsuario)
            ->delete(route('comments.destroy', $comment));

        $response->assertForbidden();

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => 'Comentário protegido',
        ]);
    }

    public function test_autor_pode_atualizar_comentario(): void
    {
        $autor = User::factory()->create();

        $post = $autor->posts()->create([
            'title' => 'Post de teste',
            'content' => 'Conteúdo do post',
        ]);

        $comment = $post->comments()->make([
            'content' => 'Comentário original',
        ]);

        $comment->user()->associate($autor);
        $comment->save();

        $response = $this->actingAs($autor)
            ->put(route('comments.update', $comment), [
                'content' => 'Comentário atualizado',
            ]);

        $response->assertRedirect(
            route('posts.show', $post)
        );

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => 'Comentário atualizado',
        ]);
    }

    public function test_autor_pode_excluir_comentario(): void
    {
        $autor = User::factory()->create();

        $post = $autor->posts()->create([
            'title' => 'Post de teste',
            'content' => 'Conteúdo do post',
        ]);

        $comment = $post->comments()->make([
            'content' => 'Comentário para excluir',
        ]);

        $comment->user()->associate($autor);
        $comment->save();

        $response = $this->actingAs($autor)
            ->delete(route('comments.destroy', $comment));

        $response->assertRedirect(
            route('posts.show', $post)
        );

        $this->assertDatabaseMissing('comments', [
            'id' => $comment->id,
        ]);
    }
}
