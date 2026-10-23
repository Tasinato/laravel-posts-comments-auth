<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_edit_or_delete_post(): void
    {
        $user = User::factory()->create();

        $post = $user->posts()->create([
            'title' => 'Post protegido',
            'content' => 'Conteúdo protegido.',
        ]);

        $this->get(route('posts.edit', $post))
            ->assertRedirect(route('login'));

        $this->put(route('posts.update', $post), [
            'title' => 'Alterado',
            'content' => 'Conteúdo alterado.',
        ])->assertRedirect(route('login'));

        $this->delete(route('posts.destroy', $post))
            ->assertRedirect(route('login'));

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Post protegido',
        ]);
    }

    public function test_guest_cannot_edit_or_delete_comment(): void
    {
        $user = User::factory()->create();

        $post = $user->posts()->create([
            'title' => 'Post de teste',
            'content' => 'Conteúdo do post.',
        ]);

        $comment = $post->comments()->make([
            'content' => 'Comentário protegido.',
        ]);
        $comment->user()->associate($user);
        $comment->save();
        

        $this->get(route('comments.edit', $comment))
            ->assertRedirect(route('login'));

        $this->put(route('comments.update', $comment), [
            'content' => 'Comentário alterado.',
        ])->assertRedirect(route('login'));

        $this->delete(route('comments.destroy', $comment))
            ->assertRedirect(route('login'));

        $this->assertDatabaseHas('comments', [
            'id' => $comment->id,
            'content' => 'Comentário protegido.',
        ]);
    }
}
