@extends('layouts.app')

@section('content')
    <h2>Nova postagem</h2>

    <form action="{{ route('posts.store') }}" method="POST">
        @csrf

        <p>
            <label for="title">Título</label><br>
            <input type="text" id="title" name="title"
                   value="{{ old('title') }}" required>
            @error('title')
                <span style="color:red">{{ $message }}</span>
            @enderror
        </p>

        <p>
            <label for="content">Conteúdo</label><br>
            <textarea id="content" name="content"
                      rows="6" required>{{ old('content') }}</textarea>
            @error('content')
                <span style="color:red">{{ $message }}</span>
            @enderror
        </p>

        <button type="submit">Publicar</button>
    </form>
@endsection