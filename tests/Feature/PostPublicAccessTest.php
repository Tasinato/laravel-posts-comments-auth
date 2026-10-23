<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostPublicAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_posts_index(): void
    {
        $response = $this->get(route('posts.index'));

        $response->assertOk();
    }

    public function test_guest_can_view_single_post(): void
    {
        $user = User::factory()->create();

        $post = $user->posts()->create([
            'title' => 'Postagem pública de teste',
            'content' => 'Conteúdo disponível para visitantes.',
        ]);

        $response = $this->get(
            route('posts.show', $post)
        );

        $response->assertOk();
        $response->assertSeeText($post->title);
    }

    public function test_guest_cannot_access_create_form(): void
    {
        $response = $this->get(route('posts.create'));

        $response->assertRedirect(route('login'));
    }
}
