@extends('layouts.app')

@section('title', 'Editar postagem — Laravel Posts & Comments')

@section('content')
    <section class="post-detail" aria-labelledby="edit-post-title">
        <h1 id="edit-post-title" class="auth-title">Editar postagem</h1>

        <form action="{{ route('posts.update', $post) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="title" class="form-label">Título</label>

                <input
                    id="title"
                    type="text"
                    name="title"
                    class="form-input"
                    value="{{ old('title', $post->title) }}"
                    required
                    @error('title')
                        aria-invalid="true"
                        aria-describedby="title-error"
                    @enderror
                >

                @error('title')
                    <p id="title-error" class="form-error">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="form-group">
                <label for="content" class="form-label">Conteúdo</label>

                <textarea
                    id="content"
                    name="content"
                    class="form-textarea"
                    rows="6"
                    required
                    @error('content')
                        aria-invalid="true"
                        aria-describedby="content-error"
                    @enderror
                >{{ old('content', $post->content) }}</textarea>

                @error('content')
                    <p id="content-error" class="form-error">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="action-group">
                <button type="submit" class="button button-primary">
                    Salvar alterações
                </button>

                <a
                    href="{{ route('posts.show', $post) }}"
                    class="button button-secondary"
                >
                    Cancelar
                </a>
            </div>
        </form>
    </section>
@endsection