<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        Notification::fake();
    }

    private function resetData(User $user, string $token, string $password = 'uma frase longa e exclusiva'): array
    {
        return ['email' => $user->email, 'token' => $token, 'password' => $password, 'password_confirmation' => $password];
    }

    public function test_recovery_and_reset_forms_are_public_and_linked_from_login(): void
    {
        $this->get(route('login'))->assertSee('Esqueci minha senha')->assertSee(route('password.request'));
        $this->get(route('password.request'))->assertOk();
        $this->get(route('password.reset', ['token' => 'test-token', 'email' => 'demo@example.com']))->assertOk();
    }

    public function test_request_uses_same_response_for_known_and_unknown_addresses_and_does_not_expose_token(): void
    {
        $user = User::factory()->create();
        $known = $this->post(route('password.email'), ['email' => $user->email]);
        $known->assertSessionHas('status')->assertSessionHasNoErrors();
        $message = session('status');
        Notification::assertSentTo($user, ResetPasswordNotification::class);
        $unknown = $this->post(route('password.email'), ['email' => 'unknown@example.com']);
        $unknown->assertSessionHas('status', $message)->assertSessionHasNoErrors();
        $this->assertSame($known->status(), $unknown->status());
        Notification::assertCount(1);
        $this->assertDatabaseCount('password_reset_tokens', 1);
        $this->assertArrayNotHasKey('token', session()->all());
    }

    public function test_reset_link_uses_configured_origin_instead_of_request_host(): void
    {
        config(['app.url' => 'https://trusted.example']);
        $user = User::factory()->create();
        $this->withHeaders(['Host' => 'untrusted.example'])->post('/forgot-password', ['email' => $user->email]);
        Notification::assertSentTo($user, ResetPasswordNotification::class, function ($notification) use ($user) {
            $url = $notification->toMail($user)->actionUrl;
            $this->assertStringStartsWith('https://trusted.example/reset-password/', $url);
            $this->assertStringNotContainsString('untrusted.example', $url);

            return true;
        });
    }

    public function test_repeated_requests_do_not_send_another_email_during_broker_cooldown(): void
    {
        $user = User::factory()->create();
        $this->post(route('password.email'), ['email' => $user->email]);
        $this->post(route('password.email'), ['email' => $user->email]);
        Notification::assertCount(1);
    }

    public function test_recovery_requests_are_rate_limited_even_for_unknown_accounts(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('password.email'), ['email' => 'unknown@example.com'])->assertRedirect();
        }
        $this->post(route('password.email'), ['email' => 'unknown@example.com'])->assertStatus(429);
    }

    public function test_valid_token_resets_password_hashes_storage_rotates_remember_token_and_cannot_be_reused(): void
    {
        $user = User::factory()->create(['remember_token' => 'previous-token']);
        $token = Password::createToken($user);
        $storedToken = DB::table('password_reset_tokens')->where('email', $user->email)->value('token');
        $this->assertNotSame($token, $storedToken);
        $this->assertTrue(Hash::check($token, $storedToken));
        $this->post(route('password.update'), $this->resetData($user, $token))->assertRedirect(route('login'))->assertSessionHas('status');
        $user->refresh();
        $this->assertTrue(Hash::check('uma frase longa e exclusiva', $user->password));
        $this->assertNotSame('previous-token', $user->remember_token);
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => $user->email]);
        $this->assertGuest();
        $this->post(route('password.update'), $this->resetData($user, $token))->assertSessionHasErrors('email');
    }

    public function test_invalid_or_expired_tokens_do_not_change_password(): void
    {
        $user = User::factory()->create();
        $original = $user->password;
        $this->post(route('password.update'), $this->resetData($user, 'invalid'))->assertSessionHasErrors('email');
        $token = Password::createToken($user);
        $this->travel(61)->minutes();
        $this->post(route('password.update'), $this->resetData($user, $token))->assertSessionHasErrors('email');
        $this->assertSame($original, $user->fresh()->password);
    }

    public function test_token_for_one_user_cannot_reset_another_user(): void
    {
        $first = User::factory()->create();
        $second = User::factory()->create();
        $token = Password::createToken($first);
        $this->post(route('password.update'), $this->resetData($second, $token))->assertSessionHasErrors('email');
        $this->assertTrue(Password::tokenExists($first, $token));
    }

    public function test_short_mismatched_and_overlong_unicode_passwords_are_rejected_without_consuming_token(): void
    {
        $user = User::factory()->create();
        $token = Password::createToken($user);
        foreach (['short', str_repeat('á', 40)] as $invalid) {
            $this->post(route('password.update'), $this->resetData($user, $token, $invalid))->assertSessionHasErrors('password');
        }
        $data = $this->resetData($user, $token);
        $data['password_confirmation'] = 'different confirmation';
        $this->post(route('password.update'), $data)->assertSessionHasErrors('password');
        $this->assertTrue(Password::tokenExists($user, $token));
    }

    public function test_reset_removes_only_the_users_database_sessions(): void
    {
        config(['session.driver' => 'database']);
        $user = User::factory()->create();
        $other = User::factory()->create();
        foreach (['first' => $user->id, 'second' => $user->id, 'other' => $other->id] as $id => $userId) {
            DB::table('sessions')->insert(['id' => $id, 'user_id' => $userId, 'payload' => base64_encode(serialize([])), 'last_activity' => time()]);
        }
        $token = Password::createToken($user);
        $this->post(route('password.update'), $this->resetData($user, $token))->assertRedirect(route('login'));
        $this->assertDatabaseMissing('sessions', ['user_id' => $user->id]);
        $this->assertDatabaseHas('sessions', ['id' => 'other']);
    }
}
