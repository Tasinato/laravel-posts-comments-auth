
@extends('layouts.app')

@section('title', 'Postagens | Laravel Posts & Comments')

@section('content')
    <section aria-labelledby="posts-title">

        <div class="posts-header">
            <div>
                <h1 id="posts-title">Postagens</h1>
                <p class="posts-description">
                    Explore as publicações e participe das conversas.
                </p>
            </div>

            @auth
                <a href="{{ route('posts.create') }}"
                   class="button button-primary">
                    Nova postagem
                </a>
            @endauth
        </div>

        <div class="posts-grid">
            @forelse ($posts as $post)
                <article class="post-card">
                    <h2 class="post-card-title">
                        <a href="{{ route('posts.show', $post) }}">
                            {{ $post->title }}
                        </a>
                    </h2>

                    <p class="post-card-meta">
                        Por {{ $post->user->name }}
                        @if ($post->created_at)
                            ·
                            <time datetime="{{ $post->created_at->toDateString() }}">
                                {{ $post->created_at->format('d/m/Y') }}
                            </time>
                        @endif
                    </p>

                    <p class="post-card-excerpt">
                        {{ \Illuminate\Support\Str::limit($post->content, 150) }}
                    </p>

                    <a href="{{ route('posts.show', $post) }}"
                       class="post-card-link"
                       aria-label="Ler postagem: {{ $post->title }}">
                        Ler postagem
                    </a>
                </article>
            @empty
                <div class="posts-empty">
                    <h2>Nenhuma postagem cadastrada</h2>
                    <p>
                        Ainda não existem publicações disponíveis.
                    </p>

                    @auth
                        <a href="{{ route('posts.create') }}"
                           class="button button-primary">
                            Criar primeira postagem
                        </a>
                    @else
                        <p>
                            <a href="{{ route('login') }}">Entre na sua conta</a>
                            para começar a publicar.
                        </p>
                    @endauth
                </div>
            @endforelse
        </div>

        <div class="posts-pagination">
            {{ $posts->links() }}
        </div>
    </section>
@endsection
