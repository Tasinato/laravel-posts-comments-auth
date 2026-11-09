@extends('layouts.app')
@section('title', 'Editar comentário')
@section('content')
<div class="editor-width">
<a class="back-link" href="{{ route('posts.show', $comment->post_id) }}">← Voltar à postagem</a>
<div class="page-heading">
<p class="eyebrow">CONTINUE A CONVERSA</p>
<h1>Revisar também<br>é <em>construir.</em>
</h1>
<p>Atualize seu comentário.</p>
</div>
<form class="form-card" action="{{ route('comments.update', $comment) }}" method="POST">@csrf @method('PUT')<div class="field">
<label for="content">Comentário</label>
<textarea id="content" name="content" rows="6" required @error('content') aria-invalid="true" aria-describedby="content-error" @enderror>{{ old('content', $comment->content) }}</textarea>@error('content')<p id="content-error" class="field-error" role="alert">{{ $message }}</p>@enderror</div>
<div class="form-actions">
<a class="text-link" href="{{ route('posts.show', $comment->post_id) }}">Cancelar</a>
<button class="button" type="submit">Salvar alterações ↗</button>
</div>
</form>
</div>
@endsection
