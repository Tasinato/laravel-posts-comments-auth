@extends('layouts.app')

@section('content')
    <article>
        <h2>{{ $post->title }}</h2>

        <p>Autor: {{ $post->user->name }}</p>

        <p style="white-space: pre-wrap;">{{ $post->content }}</p>
    </article>

    @auth
        @if (auth()->id() === $post->user_id)
            <a href="{{ route('posts.edit', $post) }}">
                Editar
            </a>

            <form action="{{ route('posts.destroy', $post) }}"
                  method="POST"
                  onsubmit="return confirm('Excluir esta postagem?')">
                @csrf
                @method('DELETE')

                <button type="submit">Excluir</button>
            </form>
        @endif
    @endauth

    <p>
        <a href="{{ route('posts.index') }}">
            Voltar às postagens
        </a>
    </p>


<hr>

<h3>Comentários</h3>

@forelse ($post->comments as $comment)
    <div>
        <p>
            <strong>{{ $comment->user->name }}</strong>
        </p>

        <p>{{ $comment->content }}</p>

        @auth
            @if (auth()->id() === $comment->user_id)
                <a href="{{ route('comments.edit', $comment) }}">
                    Editar comentário
                </a>

                <form
                    action="{{ route('comments.destroy', $comment) }}"
                    method="POST"
                    onsubmit="return confirm('Excluir este comentário?')"
                >
                    @csrf
                    @method('DELETE')

                    <button type="submit">Excluir comentário</button>
                </form>
            @endif
        @endauth
    </div>

    <hr>
@empty
    <p>Esta postagem ainda não possui comentários.</p>
@endforelse







@auth
    <h3>Adicionar comentário</h3>

    <form
        action="{{ route('posts.comments.store', $post) }}"
        method="POST"
    >
        @csrf

        <p>
            <label for="content">Comentário</label><br>

            <textarea
                id="content"
                name="content"
                rows="4"
                required
            >{{ old('content') }}</textarea>

            @error('content')
                <span style="color:red">{{ $message }}</span>
            @enderror
        </p>

        <button type="submit">Comentar</button>
    </form>
@else
    <p>
        <a href="{{ route('login') }}">Entre</a>
        para deixar um comentário.
    </p>
@endauth




@endsection