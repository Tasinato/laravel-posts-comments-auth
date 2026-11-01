@extends('layouts.app')

@section('title', 'Criar conta — Laravel Posts & Comments')

@section('content')
    <section class="auth-form" aria-labelledby="register-title">
        <h1 id="register-title" class="auth-title">Criar conta</h1>

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nome</label>

                <input
                    id="name"
                    type="text"
                    name="name"
                    class="form-input"
                    value="{{ old('name') }}"
                    autocomplete="name"
                    required
                    @error('name')
                        aria-invalid="true"
                        aria-describedby="name-error"
                    @enderror
                >

                @error('name')
                    <p id="name-error" class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">E-mail</label>

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

                @error('email')
                    <p id="email-error" class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Senha</label>

                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-input"
                    autocomplete="new-password"
                    required
                    @error('password')
                        aria-invalid="true"
                        aria-describedby="password-error"
                    @enderror
                >

                @error('password')
                    <p id="password-error" class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">
                    Confirmar senha
                </label>

                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    class="form-input"
                    autocomplete="new-password"
                    required
                    @error('password_confirmation')
                        aria-invalid="true"
                        aria-describedby="password-confirmation-error"
                    @enderror
                >

                @error('password_confirmation')
                    <p id="password-confirmation-error" class="form-error">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button type="submit" class="button button-primary">
                Cadastrar
            </button>
        </form>
    </section>
@endsection
