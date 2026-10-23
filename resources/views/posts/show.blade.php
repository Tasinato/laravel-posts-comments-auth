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
@endsection