<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\AuthValidation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

// O controller realiza validação de campos, exige confirmação de senha, aplica hash na senha antes de salvar e controla a sessão autenticada;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        AuthValidation::normalizeEmail($request);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => AuthValidation::passwordRules(),
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put('password_hash_web', Auth::guard('web')->hashPasswordForCookie(Auth::user()->getAuthPassword()));

        return redirect()->route('dashboard');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        AuthValidation::normalizeEmail($request);
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'max:4096'],
        ]);

        $key = 'login:'.hash('sha256', $credentials['email'].'|'.$request->ip());
        if (RateLimiter::tooManyAttempts($key, 5)) {
            throw ValidationException::withMessages(['email' => 'Muitas tentativas. Aguarde '.RateLimiter::availableIn($key).' segundos antes de tentar novamente.'])->status(429);
        }

        if (Auth::attempt($credentials)) {
            RateLimiter::clear($key);
            $request->session()->regenerate();
            $request->session()->put('password_hash_web', Auth::guard('web')->hashPasswordForCookie(Auth::user()->getAuthPassword()));

            return redirect()->intended(route('dashboard'));
        }

        RateLimiter::hit($key, 60);

        return back()
            ->withErrors([
                'email' => 'E-mail ou senha inválidos.',
            ])
            ->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
