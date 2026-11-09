<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AuthValidation;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class PasswordResetController extends Controller
{
    public function create()
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request)
    {
        AuthValidation::normalizeEmail($request);
        $request->validate(['email' => ['required', 'string', 'email', 'max:255']]);
        try {
            Password::sendResetLink($request->only('email'));
        } catch (TransportExceptionInterface) {
            // Não registrar endereço, token, conteúdo do e-mail ou credenciais SMTP.
            Log::warning('Falha no transporte de recuperação de senha. Verifique a configuração do serviço de e-mail.');
        }

        // Mesmo texto para e-mail existente, desconhecido ou aguardando novo envio.
        return back()->with('status', 'Se houver uma conta com esse e-mail, você receberá um link para redefinir a senha. Verifique também a pasta de spam.');
    }

    public function edit(Request $request, string $token)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => is_string($request->query('email')) ? $request->query('email') : '']);
    }

    public function update(Request $request)
    {
        AuthValidation::normalizeEmail($request);
        $request->validate([
            'token' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => AuthValidation::passwordRules(),
        ]);

        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function (User $user, string $password): void {
            $user->forceFill([
                'password' => Hash::make($password),
                'remember_token' => Str::random(60),
            ])->save();
            if (config('session.driver') === 'database') {
                DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))->where('user_id', $user->getAuthIdentifier())->delete();
            }
            event(new PasswordReset($user));
        });

        if ($status !== Password::PASSWORD_RESET) {
            return back()->withErrors(['email' => 'Este link é inválido ou expirou. Solicite um novo link de recuperação.'])->onlyInput('email');
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Senha redefinida. Entre com a nova senha.');
    }
}
