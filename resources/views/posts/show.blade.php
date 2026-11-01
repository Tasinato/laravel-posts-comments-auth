@extends('layouts.app')

@section('title', $post->title . ' — Laravel Posts & Comments')

@section('content')

    {{-- ========================================
         1. Detalhes da postagem
    ======================================== --}}

    <article class="post-detail">

        {{-- Título principal --}}
        <h1 class="post-detail-title">
            {{ $post->title }}
        </h1>

        {{-- Autor da postagem --}}
        <p class="post-detail-meta">
            Autor: {{ $post->user->name }}
        </p>

        {{-- Conteúdo completo --}}
        <div class="post-detail-content">{{ $post->content }}</div>

    </article>


    {{-- ========================================
         2. Ações da postagem
         Disponíveis apenas ao autor
    ======================================== --}}

    <div class="post-detail-actions">

        @can('update', $post)
            <a
                href="{{ route('posts.edit', $post) }}"
                class="button button-secondary"
            >
                Editar postagem
            </a>
        @endcan

        @can('delete', $post)
            <form
                action="{{ route('posts.destroy', $post) }}"
                method="POST"
                onsubmit="return confirm('Excluir esta postagem?')"
            >
                @csrf
                @method('DELETE')

                <button type="submit" class="button button-secondary">
                    Excluir postagem
                </button>
            </form>
        @endcan

        <a
            href="{{ route('posts.index') }}"
            class="button button-secondary"
        >
            Voltar às postagens
        </a>

    </div>


    {{-- ========================================
         3. Seção de comentários
    ======================================== --}}

    <section
        class="comments-section"
        aria-labelledby="comments-title"
    >

        <h2 id="comments-title">
            Comentários
        </h2>

        {{-- Lista de comentários --}}
        @forelse ($post->comments as $comment)

            <article class="comment-card">

                {{-- Autor do comentário --}}
                <h3 class="comment-author">
                    {{ $comment->user->name }}
                </h3>

                {{-- Conteúdo do comentário --}}
                <p class="comment-content">{{ $comment->content }}</p>

                {{-- Ações disponíveis ao autor --}}
                <div class="action-group">

                    @can('update', $comment)
                        <a
                            href="{{ route('comments.edit', $comment) }}"
                            class="button button-secondary"
                        >
                            Editar comentário
                        </a>
                    @endcan

                    @can('delete', $comment)
                        <form
                            action="{{ route('comments.destroy', $comment) }}"
                            method="POST"
                            onsubmit="return confirm('Excluir este comentário?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="submit"
                                class="button button-secondary"
                            >
                                Excluir comentário
                            </button>
                        </form>
                    @endcan

                </div>

            </article>

        @empty

            <p>
                Esta postagem ainda não possui comentários.
            </p>

        @endforelse


        {{-- ========================================
             4. Formulário de comentário
        ======================================== --}}

        @auth

            <h2 id="comment-form-title">
                Adicionar comentário
            </h2>

            <form
                action="{{ route('posts.comments.store', $post) }}"
                method="POST"
                aria-labelledby="comment-form-title"
            >
                @csrf

                <div class="form-group">

                    <label for="content" class="form-label">
                        Comentário
                    </label>

                    <textarea
                        id="content"
                        name="content"
                        rows="4"
                        class="form-textarea"
                        required
                        @error('content')
                            aria-invalid="true"
                            aria-describedby="content-error"
                        @enderror
                    >{{ old('content') }}</textarea>

                    @error('content')
                        <p id="content-error" class="form-error">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                <button type="submit" class="button button-primary">
                    Comentar
                </button>

            </form>

        @else

            <p>
                <a href="{{ route('login') }}" class="button button-secondary">

                    Entre
                </a>
                para deixar um comentário.
            </p>

        @endauth

    </section>

@endsection
