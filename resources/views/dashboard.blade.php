@extends('layouts.app')
@section('title', 'Meu painel')
@section('content')
<section class="page-heading">
<p class="eyebrow">
<span class="tiny-dot">
</span> SEU ESPAÇO</p>
<h1>Olá, {{ Str::before(auth()->user()->name, ' ') }}.<br>Que tal <em>compartilhar?</em>
</h1>
<p>Uma nova ideia pode ser o começo de uma boa conversa.</p>
</section>
<div class="dashboard-grid">
<section class="dashboard-card">
<span class="feature-number">01 / ESCREVER</span>
<h2>Dê espaço às suas ideias.</h2>
<p>Publique um aprendizado, uma experiência ou uma pergunta.</p>
<a class="button" href="{{ route('posts.create') }}">Nova postagem ↗</a>
</section>
<section class="dashboard-card">
<span class="feature-number">02 / DESCOBRIR</span>
<h2>Outras perspectivas.</h2>
<p>Explore as postagens e participe das conversas com um comentário.</p>
<a class="button button-outline" href="{{ route('posts.index') }}">Explorar postagens ↗</a>
</section>
</div>
<div class="account-details">
<span class="eyebrow">SUA CONTA</span>
<p>{{ auth()->user()->name }} <span>· {{ auth()->user()->email }}</span>
</p>
</div>
@endsection
