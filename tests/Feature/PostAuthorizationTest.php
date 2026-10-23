<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_outro_usuario_nao_pode_atualizar_post(): void
    {
        $autor = User::factory()->create();
        $outroUsuario = User::factory()->create();

        $post = $autor->posts()->create([
            'title' => 'Post original',
            'content' => 'Conteúdo original',
        ]);

        $response = $this->actingAs($outroUsuario)
            ->put(route('posts.update', $post), [
                'title' => 'Post alterado',
                'content' => 'Alteração indevida',
            ]);

        $response->assertForbidden();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'user_id' => $autor->id,
            'title' => 'Post original',
            'content' => 'Conteúdo original',
        ]);
    }

    public function test_outro_usuario_nao_pode_excluir_post(): void
    {
        $autor = User::factory()->create();
        $outroUsuario = User::factory()->create();

        $post = $autor->posts()->create([
            'title' => 'Post protegido',
            'content' => 'Conteúdo protegido',
        ]);

        $response = $this->actingAs($outroUsuario)
            ->delete(route('posts.destroy', $post));

        $response->assertForbidden();

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'user_id' => $autor->id,
            'title' => 'Post protegido',
        ]);
    }

    public function test_autor_pode_atualizar_post(): void
    {
        $autor = User::factory()->create();

        $post = $autor->posts()->create([
            'title' => 'Título original',
            'content' => 'Conteúdo original',
        ]);

        $response = $this->actingAs($autor)
            ->put(route('posts.update', $post), [
                'title' => 'Título atualizado',
                'content' => 'Conteúdo atualizado',
            ]);

        $response->assertRedirect(route('posts.show', $post));

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Título atualizado',
        ]);
    }

    public function test_autor_pode_excluir_post(): void
    {
        $autor = User::factory()->create();

        $post = $autor->posts()->create([
            'title' => 'Post para excluir',
            'content' => 'Conteúdo de teste',
        ]);

        $response = $this->actingAs($autor)
            ->delete(route('posts.destroy', $post));

        $response->assertRedirect(route('posts.index'));

        $this->assertDatabaseMissing('posts', [
            'id' => $post->id,
        ]);
    }
}
