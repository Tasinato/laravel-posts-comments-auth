@extends('layouts.app')
@section('title', 'Editar postagem')
@section('content')
<div class="editor-width">
<a class="back-link" href="{{ route('posts.show', $post) }}">← Voltar à postagem</a>
<div class="page-heading">
<p class="eyebrow">CUIDE DO QUE VOCÊ COMPARTILHA</p>
<h1>Novas palavras.<br>A mesma <em>ideia.</em>
</h1>
<p>Revise sua postagem e salve as alterações.</p>
</div>
<form class="form-card" action="{{ route('posts.update', $post) }}" method="POST">@csrf @method('PUT') @include('posts._form')</form>
</div>
@endsection
