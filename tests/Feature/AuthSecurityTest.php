<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
    }

    public function test_registration_normalizes_email_and_requires_a_long_confirmed_password(): void
    {
        $this->post(route('register'), ['name' => 'Ana', 'email' => 'ANA@EXAMPLE.COM', 'password' => 'short', 'password_confirmation' => 'short'])->assertSessionHasErrors('password');
        $this->post(route('register'), ['name' => 'Ana', 'email' => 'ANA@EXAMPLE.COM', 'password' => 'uma frase longa de cadastro', 'password_confirmation' => 'uma frase longa de cadastro'])->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('users', ['email' => 'ana@example.com']);
        $this->assertAuthenticated();
        $this->assertNotNull(session('password_hash_web'));
    }

    public function test_existing_mixed_case_email_can_log_in_without_creating_a_duplicate_account(): void
    {
        $user = User::factory()->create(['email' => 'Legacy@Example.COM', 'password' => Hash::make('existing-password')]);
        $this->post(route('register'), ['name' => 'Another', 'email' => 'legacy@example.com', 'password' => 'uma frase longa exclusiva', 'password_confirmation' => 'uma frase longa exclusiva'])->assertSessionHasErrors('email');
        $this->post(route('login'), ['email' => 'legacy@example.com', 'password' => 'existing-password'])->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_non_string_login_credentials_are_validation_errors(): void
    {
        $this->post(route('login'), ['email' => ['bad'], 'password' => ['bad']])->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }

    public function test_failed_login_is_generic_does_not_flash_password_and_rate_limits_the_account_ip_pair(): void
    {
        $user = User::factory()->create(['email' => 'ana@example.com', 'password' => Hash::make('correct-password')]);
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('login'), ['email' => 'ANA@EXAMPLE.COM', 'password' => 'wrong-password'])->assertSessionHasErrors('email');
        }
        $this->assertSame('E-mail ou senha inválidos.', session('errors')->first('email'));
        $this->assertNull(session()->getOldInput('password'));
        $this->postJson(route('login'), ['email' => $user->email, 'password' => 'correct-password'])->assertStatus(429);
        $this->assertGuest();
        $this->travel(61)->seconds();
        $this->post(route('login'), ['email' => 'ANA@EXAMPLE.COM', 'password' => 'correct-password'])->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->assertNotNull(session('password_hash_web'));
        $this->assertSame(0, RateLimiter::attempts('login:'.hash('sha256', 'ana@example.com|127.0.0.1')));
    }

    public function test_a_session_with_the_previous_password_hash_loses_access(): void
    {
        $user = User::factory()->create();
        $oldHash = $user->password;
        $user->forceFill(['password' => Hash::make('new secure password')])->save();
        $this->actingAs($user)->withSession(['password_hash_web' => $oldHash])->get(route('dashboard'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_logout_revokes_authenticated_access(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->post(route('logout'))->assertRedirect(route('home'));
        $this->assertGuest();
        $this->get(route('dashboard'))->assertRedirect(route('login'));
    }

    public function test_security_headers_and_csp_are_present_with_debug_disabled(): void
    {
        config(['app.debug' => false]);
        $this->get(route('password.request'))->assertOk()
            ->assertHeader('X-Content-Type-Options', 'nosniff')
            ->assertHeader('X-Frame-Options', 'DENY')
            ->assertHeader('Referrer-Policy', 'no-referrer')
            ->assertHeader('Content-Security-Policy', "default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; font-src 'self'; connect-src 'self'; object-src 'none'; base-uri 'none'; form-action 'self'; frame-ancestors 'none'");
    }

    public function test_post_content_is_escaped_instead_of_executed_as_html(): void
    {
        $user = User::factory()->create();
        $post = $user->posts()->create(['title' => '<script>alert(1)</script>', 'content' => '<img src=x onerror=alert(1)>']);
        $this->get(route('posts.show', $post))->assertOk()->assertDontSee('<script>alert(1)</script>', false)->assertDontSee('<img src=x onerror=alert(1)>', false)->assertSee('&lt;script&gt;', false);
    }
}
