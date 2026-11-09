<div class="field">
<label for="title">Título <span class="field-hint">até 255 caracteres</span>
</label>
<input type="text" id="title" name="title" value="{{ old('title', $post->title ?? '') }}" maxlength="255" required placeholder="Dê um título à sua ideia" @error('title') aria-invalid="true" aria-describedby="title-error" @enderror>@error('title')<p class="field-error" id="title-error" role="alert">{{ $message }}</p>@enderror</div>
<div class="field">
<label for="content">Conteúdo</label>
<textarea id="content" name="content" rows="10" required placeholder="O que você quer compartilhar hoje?" @error('content') aria-invalid="true" aria-describedby="content-error" @enderror>{{ old('content', $post->content ?? '') }}</textarea>@error('content')<p class="field-error" id="content-error" role="alert">{{ $message }}</p>@enderror<p class="field-hint">O texto será publicado como você escrever, preservando as quebras de linha.</p>
</div>
<div class="form-actions">
<a class="text-link" href="{{ isset($post) ? route('posts.show', $post) : route('posts.index') }}">Cancelar</a>
<button class="button" type="submit">{{ isset($post) ? 'Salvar alterações' : 'Publicar postagem' }} <span aria-hidden="true">↗</span>
</button>
</div>
