<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_comment(): void
    {
        $author = User::factory()->create();
        $user = User::factory()->create();

        $post = $author->posts()->create([
            'title' => 'Postagem de teste',
            'content' => 'Conteúdo da postagem.',
        ]);

        $response = $this->actingAs($user)
            ->post(route('posts.comments.store', $post), [
                'content' => 'Novo comentário.',
            ]);

        $response->assertRedirect(
            route('posts.show', $post)
        );

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'content' => 'Novo comentário.',
        ]);
    }

    public function test_guest_cannot_create_comment(): void
    {
        $author = User::factory()->create();

        $post = $author->posts()->create([
            'title' => 'Postagem de teste',
            'content' => 'Conteúdo da postagem.',
        ]);

        $response = $this->post(
            route('posts.comments.store', $post),
            ['content' => 'Comentário não autorizado.']
        );

        $response->assertRedirect(route('login'));

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_authenticated_user_cannot_create_empty_comment(): void
    {
        $user = User::factory()->create();

        $post = $user->posts()->create([
            'title' => 'Postagem de teste',
            'content' => 'Conteúdo da postagem.',
        ]);

        $response = $this->actingAs($user)
            ->post(route('posts.comments.store', $post), [
                'content' => '',
            ]);

        $response->assertSessionHasErrors('content');

        $this->assertDatabaseCount('comments', 0);
    }
}
