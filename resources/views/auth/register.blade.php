@extends('layouts.app')
@section('title', 'Criar conta')
@section('content')
<div class="auth-layout">
<section class="auth-intro">
<p class="eyebrow">IDEIAS CONECTAM PESSOAS</p>
<h1>Faça parte<br>da <em>conversa.</em>
</h1>
<p class="hero-description">Um lugar para aprender com outros olhares e dar voz ao que você pensa.</p>
<div class="auth-quote">
<span class="quote-mark" aria-hidden="true">“</span>
<p>Compartilhar uma ideia<br>é abrir uma possibilidade.</p>
</div>
</section>
<section class="auth-card" aria-labelledby="auth-heading">
<span class="card-label">SEU ESPAÇO DE TROCA</span>
<h2 id="auth-heading">Criar minha conta</h2>
<p class="muted">Crie sua conta para compartilhar suas ideias e comentar.</p>
<form action="{{ route('register') }}" method="POST">@csrf
<div class="field">
<label for="name">Nome</label>
<input id="name" name="name" type="text" autocomplete="name" required value="{{ old('name') }}" @error('name') aria-invalid="true" aria-describedby="name-error" @enderror>@error('name')<p id="name-error" class="field-error" role="alert">{{ $message }}</p>@enderror</div>
<div class="field">
<label for="email">E-mail</label>
<input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>@error('email')<p id="email-error" class="field-error" role="alert">{{ $message }}</p>@enderror</div>
<div class="field">
<label for="password">Senha</label>
<input id="password" name="password" type="password" autocomplete="new-password" required @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>@error('password')<p id="password-error" class="field-error" role="alert">{{ $message }}</p>@enderror</div>
<div class="field">
<label for="password_confirmation">Confirmar senha</label>
<input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required @error('password_confirmation') aria-invalid="true" aria-describedby="password_confirmation-error" @enderror>@error('password_confirmation')<p id="password_confirmation-error" class="field-error" role="alert">{{ $message }}</p>@enderror</div>
<button class="button button-full" type="submit">Criar minha conta <span aria-hidden="true">↗</span>
</button>
</form>
<p class="auth-switch">Já tem uma conta? <a href="{{ route('login') }}">Entrar</a>
</p>
</section>
</div>
@endsection
