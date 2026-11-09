@extends('layouts.app')
@section('title', 'Redefinir senha')
@section('content')
<div class="auth-layout">
    <section class="auth-intro">
        <p class="eyebrow">UM NOVO COMEÇO</p>
        <h1>Nova senha.<br>A mesma <em>conversa.</em></h1>
        <p class="hero-description">Escolha uma senha exclusiva para sua conta. Você pode usar uma frase longa ou um gerenciador de senhas.</p>
    </section>
    <section class="auth-card" aria-labelledby="auth-heading">
        <span class="card-label">RECUPERE SEU ESPAÇO</span>
        <h2 id="auth-heading">Criar nova senha</h2>
        <p class="muted">O link é válido por {{ config('auth.passwords.users.expire') }} minutos e pode ser usado uma única vez.</p>
        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            @error('token') <p class="field-error" role="alert">O link não é válido. Solicite um novo link abaixo.</p> @enderror
            <div class="field">
                <label for="email">E-mail da sua conta</label>
                <input id="email" name="email" type="email" autocomplete="email" maxlength="255" value="{{ old('email', $email) }}" required @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                @error('email') <p id="email-error" class="field-error" role="alert">{{ $message }}</p> @enderror
            </div>
            <div class="field">
                <label for="password">Nova senha</label>
                <input id="password" name="password" type="password" autocomplete="new-password" minlength="12" required aria-describedby="password-help @error('password') password-error @enderror" @error('password') aria-invalid="true" @enderror>
                <p class="field-hint" id="password-help">Pelo menos 12 caracteres. Prefira uma frase longa e não reutilize senhas de outros serviços.</p>
                @error('password') <p id="password-error" class="field-error" role="alert">{{ $message }}</p> @enderror
            </div>
            <div class="field">
                <label for="password_confirmation">Confirmar nova senha</label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="12" required>
            </div>
            <button class="button button-full" type="submit">Salvar nova senha <span aria-hidden="true">↗</span></button>
        </form>
        <p class="auth-switch">Link expirado? <a href="{{ route('password.request') }}">Solicitar um novo link</a></p>
    </section>
</div>
@endsection
