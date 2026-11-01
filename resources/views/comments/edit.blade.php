@extends('layouts.app')

@section('title', 'Editar comentário — Laravel Posts & Comments')

@section('content')
    <section class="post-detail" aria-labelledby="edit-comment-title">
        <h1 id="edit-comment-title" class="auth-title">
            Editar comentário
        </h1>

        <form action="{{ route('comments.update', $comment) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="content" class="form-label">Comentário</label>

                <textarea
                    id="content"
                    name="content"
                    class="form-textarea"
                    rows="5"
                    required
                    @error('content')
                        aria-invalid="true"
                        aria-describedby="content-error"
                    @enderror
                >{{ old('content', $comment->content) }}</textarea>

                @error('content')
                    <p id="content-error" class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="action-group">
                <button type="submit" class="button button-primary">
                    Salvar alterações
                </button>

                <a
                    href="{{ route('posts.show', $comment->post_id) }}"
                    class="button button-secondary"
                >
                    Cancelar
                </a>
            </div>
        </form>
    </section>
@endsection