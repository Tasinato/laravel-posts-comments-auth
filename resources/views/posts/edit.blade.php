@extends('layouts.app')

@section('content')
    <h2>Editar postagem</h2>

    <form action="{{ route('posts.update', $post) }}"
          method="POST">
        @csrf
        @method('PUT')

        <p>
            <label for="title">Título</label><br>
            <input type="text" id="title" name="title"
                   value="{{ old('title', $post->title) }}" required>
            @error('title')
                <span style="color:red">{{ $message }}</span>
            @enderror
        </p>

        <p>
            <label for="content">Conteúdo</label><br>
            <textarea id="content" name="content"
                      rows="6" required>{{ old('content', $post->content) }}</textarea>
            @error('content')
                <span style="color:red">{{ $message }}</span>
            @enderror
        </p>

        <button type="submit">Salvar alterações</button>
    </form>
@endsection