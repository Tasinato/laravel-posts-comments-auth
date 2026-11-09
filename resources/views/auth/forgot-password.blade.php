@extends('layouts.app')
@section('title', 'Recuperar senha')
@section('content')
<div class="auth-layout">
    <section class="auth-intro">
        <p class="eyebrow">VAMOS AJUDAR VOCÊ A VOLTAR</p>
        <h1>Esqueceu a senha?<br>Acontece com <em>todo mundo.</em></h1>
        <p class="hero-description">Solicite um link para criar uma nova senha e continuar a conversa.</p>
    </section>
    <section class="auth-card" aria-labelledby="auth-heading">
        <span class="card-label">RECUPERAÇÃO DE ACESSO</span>
        <h2 id="auth-heading">Recuperar senha</h2>
        <p class="muted">Informe o e-mail usado no cadastro. Se houver uma conta, enviaremos as instruções.</p>
        @if(session('status')) <div class="notice notice-success" role="status">{{ session('status') }}</div> @endif
        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="field">
                <label for="email">E-mail da sua conta</label>
                <input id="email" name="email" type="email" autocomplete="email" maxlength="255" required value="{{ old('email') }}" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>
                @error('email') <p class="field-error" id="email-error" role="alert">{{ $message }}</p> @enderror
            </div>
            <button class="button button-full" type="submit">Enviar link de recuperação <span aria-hidden="true">↗</span></button>
        </form>
        <p class="auth-switch">Lembrou a senha? <a href="{{ route('login') }}">Voltar para entrar</a></p>
    </section>
</div>
@endsection
