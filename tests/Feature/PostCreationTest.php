<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_create_post(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('posts.store'), [
                'title' => 'Nova postagem',
                'content' => 'Conteúdo da postagem.',
            ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'title' => 'Nova postagem',
            'content' => 'Conteúdo da postagem.',
        ]);
    }

    public function test_guest_cannot_create_post(): void
    {
        $response = $this->post(route('posts.store'), [
            'title' => 'Postagem não autorizada',
            'content' => 'Conteúdo indevido.',
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseMissing('posts', [
            'title' => 'Postagem não autorizada',
        ]);
    }

    public function test_authenticated_user_cannot_create_post_without_title(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('posts.store'), [
                'title' => '',
                'content' => 'Conteúdo válido.',
            ]);

        $response->assertSessionHasErrors('title');

        $this->assertDatabaseCount('posts', 0);
    }
}
