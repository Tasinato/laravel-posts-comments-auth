<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ReactCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_react_create_requires_authentication(): void
    {
        $this->get(route('react.posts.create.test'))
            ->assertRedirect(route('login'));
    }

    public function test_author_can_open_react_edit(): void
    {
        $author = User::factory()->create();

        $post = $author->posts()->create([
            'title' => 'Original',
            'content' => 'Conteúdo',
        ]);

        $this->actingAs($author)
            ->get(route('react.posts.edit.test', $post))
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Posts/Edit')
                ->where('post.id', $post->id));
    }

    public function test_other_user_cannot_open_react_edit(): void
    {
        $author = User::factory()->create();

        $post = $author->posts()->create([
            'title' => 'Original',
            'content' => 'Conteúdo',
        ]);

        $this->actingAs(User::factory()->create())
            ->get(route('react.posts.edit.test', $post))
            ->assertForbidden();
    }

    public function test_inertia_post_creation_returns_to_react(): void
    {
        $author = User::factory()->create();

        $response = $this->actingAs($author)
            ->withHeader('X-Inertia', 'true')
            ->post(route('posts.store'), [
                'title' => 'Nova',
                'content' => 'Conteúdo',
            ]);

        $post = $author->posts()->firstOrFail();

        $response->assertRedirect(
            route('react.posts.show.test', $post)
        );
    }

    public function test_inertia_comment_validation_preserves_database(): void
    {
        $author = User::factory()->create();

        $post = $author->posts()->create([
            'title' => 'Original',
            'content' => 'Conteúdo',
        ]);

        $this->actingAs($author)
            ->withHeader('X-Inertia', 'true')
            ->from(route('react.posts.show.test', $post))
            ->post(route('posts.comments.store', $post), [
                'content' => '   ',
            ])
            ->assertSessionHasErrors('content')
            ->assertRedirect(route('react.posts.show.test', $post));

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_inertia_comment_creation_returns_to_react(): void
    {
        $author = User::factory()->create();

        $post = $author->posts()->create([
            'title' => 'Original',
            'content' => 'Conteúdo',
        ]);

        $this->actingAs($author)
            ->withHeader('X-Inertia', 'true')
            ->post(route('posts.comments.store', $post), [
                'content' => 'Comentário',
            ])
            ->assertRedirect(route('react.posts.show.test', $post));

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $author->id,
            'content' => 'Comentário',
        ]);
    }

    public function test_inertia_comment_update_preserves_authorization(): void
    {
        $author = User::factory()->create();

        $post = $author->posts()->create([
            'title' => 'Original',
            'content' => 'Conteúdo',
        ]);

        $comment = $post->comments()->make([
            
            'content' => 'Original',
        ]);
        $comment->user()->associate($author);
        $comment->save();

        
        $this->actingAs(User::factory()->create())
            ->withHeader('X-Inertia', 'true')
            ->put(route('comments.update', $comment), [
                'content' => 'Indevido',
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => 'Original',
        ]);
    }
}
