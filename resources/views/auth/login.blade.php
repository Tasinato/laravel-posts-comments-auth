@extends('layouts.app')
@section('title', 'Entrar')
@section('content')
<div class="auth-layout">
<section class="auth-intro">
<p class="eyebrow">IDEIAS CONECTAM PESSOAS</p>
<h1>Bom ter você<br>por <em>aqui.</em>
</h1>
<p class="hero-description">Um lugar para aprender com outros olhares e dar voz ao que você pensa.</p>
<div class="auth-quote">
<span class="quote-mark" aria-hidden="true">“</span>
<p>Compartilhar uma ideia<br>é abrir uma possibilidade.</p>
</div>
</section>
<section class="auth-card" aria-labelledby="auth-heading">
<span class="card-label">SEU ESPAÇO DE TROCA</span>
<h2 id="auth-heading">Entrar</h2>
<p class="muted">Entre na sua conta para continuar compartilhando.</p>
<form action="{{ route('login') }}" method="POST">@csrf
<div class="field">
<label for="email">E-mail</label>
<input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" @error('email') aria-invalid="true" aria-describedby="email-error" @enderror>@error('email')<p id="email-error" class="field-error" role="alert">{{ $message }}</p>@enderror</div>
<div class="field">
<label for="password">Senha</label>
<input id="password" name="password" type="password" autocomplete="current-password" required @error('password') aria-invalid="true" aria-describedby="password-error" @enderror>@error('password')<p id="password-error" class="field-error" role="alert">{{ $message }}</p>@enderror</div>
<button class="button button-full" type="submit">Entrar <span aria-hidden="true">↗</span>
</button>
</form>
<p class="auth-switch">Ainda não tem conta? <a href="{{ route('register') }}">Crie a sua</a>
</p>
</section>
</div>
@endsection
