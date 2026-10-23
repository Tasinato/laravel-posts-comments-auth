@extends('layouts.app')

@section('content')
    <h2>Postagens</h2>

    @auth
        <p>
            <a href="{{ route('posts.create') }}">
                Nova postagem
            </a>
        </p>
    @endauth

    @forelse ($posts as $post)
        <article>
            <h3>
                <a href="{{ route('posts.show', $post) }}">
                    {{ $post->title }}
                </a>
            </h3>

            <p>Autor: {{ $post->user->name }}</p>
            <p>{{ Str::limit($post->content, 150) }}</p>
        </article>
        <hr>
    @empty
        <p>Nenhuma postagem cadastrada.</p>
    @endforelse

    {{ $posts->links() }}
@endsection