@extends('layouts.app')

@section('title', 'Entrar — Laravel Posts & Comments')

@section('content')

    {{-- Seção principal do formulário de autenticação --}}
    <section class="auth-form" aria-labelledby="login-title">

        {{-- Título principal da página --}}
        <h1 id="login-title" class="auth-title">
            Entrar
        </h1>

        {{-- Formulário enviado ao backend Laravel --}}
        <form action="{{ route('login') }}" method="POST">

            {{-- Token de proteção contra CSRF --}}
            @csrf

            {{-- Campo de e-mail --}}
            <div class="form-group">
                <label for="email" class="form-label">
                    E-mail
                </label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-input"
                    value="{{ old('email') }}"
                    autocomplete="email"
                    required
                    @error('email')
                        aria-invalid="true"
                        aria-describedby="email-error"
                    @enderror
                >

                {{-- Mensagem de validação do e-mail --}}
                @error('email')
                    <p id="email-error" class="form-error">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Campo de senha --}}
            <div class="form-group">
                <label for="password" class="form-label">
                    Senha
                </label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-input"
                    autocomplete="current-password"
                    required
                    @error('password')
                        aria-invalid="true"
                        aria-describedby="password-error"
                    @enderror
                >

                {{-- Mensagem de validação da senha --}}
                @error('password')
                    <p id="password-error" class="form-error">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Botão de envio --}}
            <button type="submit" class="button button-primary">
                Entrar
            </button>

        </form>
    </section>

@endsection
