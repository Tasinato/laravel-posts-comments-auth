<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

final class AuthValidation
{
    public static function normalizeEmail(Request $request): void
    {
        $email = $request->input('email');
        if (is_string($email)) {
            $normalized = Str::lower(trim($email));
            // Preserva contas antigas que tenham e-mail cadastrado com maiúsculas.
            $existing = strlen($normalized) <= 255
                ? User::query()->whereRaw('LOWER(email) = ?', [$normalized])->value('email')
                : null;
            $request->merge(['email' => $existing ?? $normalized]);
        }
    }

    public static function passwordRules(): array
    {
        return [
            'required', 'string', 'confirmed', Password::min(12),
            function (string $attribute, mixed $value, \Closure $fail): void {
                // bcrypt limita a entrada a 72 bytes, não 72 caracteres Unicode.
                if (is_string($value) && strlen($value) > 72) {
                    $fail('A senha é muito longa. Reduza o tamanho, especialmente se usar emojis ou caracteres acentuados.');
                }
            },
        ];
    }
}
