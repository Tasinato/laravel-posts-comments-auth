@extends('layouts.app')
@section('title', 'Boas ideias começam com uma conversa')
@section('content')
<section class="hero home-hero">
<div class="hero-copy">
<p class="eyebrow">
<span class="tiny-dot">
</span> UM ESPAÇO DE TROCA</p>
<h1>Boas ideias começam<br>com uma <em>conversa.</em>
</h1>
<p class="hero-description">Compartilhe o que você sabe. Descubra outros pontos de vista. Aqui, cada postagem é um convite para continuar a conversa.</p>
<div class="hero-actions">
<a class="button" href="{{ route('posts.index') }}">Explorar postagens <span aria-hidden="true">↗</span>
</a>@guest <a class="text-link" href="{{ route('register') }}">Quero participar <span aria-hidden="true">→</span>
</a> @else <a class="text-link" href="{{ route('posts.create') }}">Escrever uma postagem <span aria-hidden="true">→</span>
</a> @endguest</div>
</div>
<div class="editorial-art" aria-hidden="true">
<div class="art-circle">
</div>
<span class="art-label">IDEIAS EM MOVIMENTO</span>
<div class="art-paper">
<span class="paper-number">01 /</span>
<span class="paper-line">
</span>
<span class="paper-line short">
</span>
<span class="paper-quote">Uma ideia.<br>Novas perspectivas.<br>
<em>Mais possibilidades.</em>
</span>
<span class="paper-bottom">COMPARTILHAR É CONSTRUIR <span>↗</span>
</span>
</div>
<div class="art-sticker">vamos<br>conversar <span>↗</span>
</div>
</div>
</section>
<section class="intro-strip" aria-labelledby="como-funciona">
<div>
<p class="eyebrow">SIMPLES, COMO UMA BOA CONVERSA</p>
<h2 id="como-funciona">Seu ponto de vista tem lugar aqui.</h2>
</div>
<p>Leia livremente. Crie sua conta para publicar, comentar e fazer parte da troca.</p>
</section>
<div class="feature-grid">
<article class="feature">
<span class="feature-number">01</span>
<h3>Explore ideias</h3>
<p>Encontre postagens e acompanhe as conversas, mesmo sem uma conta.</p>
</article>
<article class="feature">
<span class="feature-number">02</span>
<h3>Compartilhe sua voz</h3>
<p>Transforme um aprendizado, uma experiência ou uma pergunta em uma postagem.</p>
</article>
<article class="feature">
<span class="feature-number">03</span>
<h3>Continue a conversa</h3>
<p>Comente, conheça outras perspectivas e cuide do conteúdo que você publica.</p>
</article>
</div>
@endsection
