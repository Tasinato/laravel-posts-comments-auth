@extends('layouts.app')
@section('title', $post->title)
@section('content')
<div class="reading-width">
<a class="back-link" href="{{ route('posts.index') }}">← Todas as postagens</a>
<article class="reading-article">
<p class="eyebrow">UMA IDEIA PARA COMPARTILHAR</p>
<h1>{{ $post->title }}</h1>
<div class="article-meta">
<div class="author">
<span class="avatar" aria-hidden="true">{{ Str::upper(Str::substr($post->user->name, 0, 1)) }}</span>
<span>{{ $post->user->name }}</span>
</div>
<time datetime="{{ $post->created_at->toDateString() }}">{{ $post->created_at->format('d/m/Y') }}</time>
</div>
<div class="post-content">{{ $post->content }}</div>
<div class="resource-actions">@can('update', $post)<a class="button button-small button-outline" href="{{ route('posts.edit', $post) }}">Editar postagem</a>@endcan @can('delete', $post)<form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Excluir esta postagem e seus comentários? Esta ação não pode ser desfeita.')">@csrf @method('DELETE')<button class="danger-button" type="submit">Excluir postagem</button>
</form>@endcan</div>
</article>
<section class="comments-section" aria-labelledby="comments-heading">
<div class="section-heading">
<div class="section-title">
<h2 id="comments-heading">A conversa continua</h2>
<span class="count-badge">{{ $post->comments->count() }}</span>
</div>
</div>
@forelse($post->comments as $comment)
<article class="comment-card">
<div class="article-meta">
<div class="author">
<span class="avatar avatar-light" aria-hidden="true">{{ Str::upper(Str::substr($comment->user->name, 0, 1)) }}</span>
<h3>{{ $comment->user->name }}</h3>
</div>
<time datetime="{{ $comment->created_at->toDateString() }}">{{ $comment->created_at->format('d/m/Y') }}</time>
</div>
<p class="comment-content">{{ $comment->content }}</p>
<div class="resource-actions">@can('update', $comment)<a class="text-link" href="{{ route('comments.edit', $comment) }}">Editar comentário</a>@endcan @can('delete', $comment)<form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Excluir este comentário? Esta ação não pode ser desfeita.')">@csrf @method('DELETE')<button class="danger-button" type="submit">Excluir comentário</button>
</form>@endcan</div>
</article>
@empty <div class="comment-empty">
<p>Esta postagem ainda não possui comentários.</p>
<span>Uma pergunta ou um novo ponto de vista pode começar a troca.</span>
</div> @endforelse
@auth <form class="comment-form" action="{{ route('posts.comments.store', $post) }}" method="POST">@csrf<h3>Qual é o seu ponto de vista?</h3>
<label for="content">Comentário</label>
<textarea id="content" name="content" rows="4" required placeholder="Participe da conversa…" @error('content') aria-invalid="true" aria-describedby="content-error" @enderror>{{ old('content') }}</textarea>@error('content')<p id="content-error" class="field-error" role="alert">{{ $message }}</p>@enderror<div class="form-actions">
<span class="muted">Compartilhe com respeito.</span>
<button class="button button-small" type="submit">Publicar comentário <span aria-hidden="true">↗</span>
</button>
</div>
</form>
@else <div class="join-box">
<div>
<h3>Sua perspectiva faz a diferença.</h3>
<p>Entre na sua conta para deixar um comentário.</p>
</div>
<a class="button button-small" href="{{ route('login') }}">Entrar na conversa ↗</a>
</div>@endauth
</section>
</div>
@endsection
