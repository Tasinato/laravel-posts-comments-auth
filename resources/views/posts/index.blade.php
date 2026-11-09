@extends('layouts.app')
@section('title', 'Postagens')
@section('content')
<section class="hero listing-hero">
<div>
<p class="eyebrow">
<span class="tiny-dot">
</span> O QUE ESTAMOS COMPARTILHANDO</p>
<h1>Ideias para ler.<br>Conversas para <em>continuar.</em>
</h1>
<p class="hero-description">Um espaço aberto para aprendizados, descobertas e novos pontos de vista.</p>
</div>
<div class="listing-note">
<span class="quote-mark" aria-hidden="true">“</span>
<p>Uma boa conversa<br>sempre abre caminhos.</p>
<span class="eyebrow">LEIA. COMPARTILHE. CONVERSE.</span>
</div>
</section>
<section aria-labelledby="posts-heading">
<div class="section-heading">
<div class="section-title">
<h2 id="posts-heading">Todas as postagens</h2>
<span class="count-badge">{{ $posts->total() }}</span>
</div>@auth <a class="button button-small" href="{{ route('posts.create') }}">
<span aria-hidden="true">＋</span> Nova postagem</a> @else <a class="text-link" href="{{ route('login') }}">Entre para escrever <span aria-hidden="true">↗</span>
</a> @endauth</div>
<div class="post-grid">
@forelse($posts as $post)
<article class="post-card">
<div class="card-top">
<span class="card-label">POSTAGEM</span>
<time datetime="{{ $post->created_at->toDateString() }}">{{ $post->created_at->format('d/m/Y') }}</time>
</div>
<h3>
<a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
</h3>
<p class="card-excerpt">{{ Str::limit($post->content, 180) }}</p>
<div class="card-bottom">
<div class="author">
<span class="avatar" aria-hidden="true">{{ Str::upper(Str::substr($post->user->name, 0, 1)) }}</span>
<span>{{ $post->user->name }}</span>
</div>
<a class="read-arrow" href="{{ route('posts.show', $post) }}" aria-label="Ler: {{ $post->title }}">↗</a>
</div>
</article>
@empty
<div class="empty-state">
<span class="empty-symbol" aria-hidden="true">✳</span>
<h3>A primeira conversa pode ser sua.</h3>
<p>Nenhuma postagem cadastrada. Compartilhe uma ideia para começar.</p>
<a class="button" href="{{ auth()->check() ? route('posts.create') : route('register') }}">{{ auth()->check() ? 'Criar postagem' : 'Criar minha conta' }} <span aria-hidden="true">↗</span>
</a>
</div>
@endforelse
</div>
<div class="pagination">{{ $posts->links() }}</div>
</section>
@endsection
