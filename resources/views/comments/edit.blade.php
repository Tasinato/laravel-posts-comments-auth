
@extends('layouts.app')

@section('content')
    <h2>Editar comentário</h2>

    <form
        action="{{ route('comments.update', $comment) }}"
        method="POST"
    >
        @csrf
        @method('PUT')

        <p>
            <label for="content">Comentário</label><br>

            <textarea
                id="content"
                name="content"
                rows="5"
                required
            >{{ old('content', $comment->content) }}</textarea>

            @error('content')
                <span style="color:red">{{ $message }}</span>
            @enderror
        </p>

        <button type="submit">Salvar alterações</button>
    </form>

    <p>
        <a href="{{ route('posts.show', $comment->post_id) }}">
            Voltar à postagem
        </a>
    </p>
@endsection
