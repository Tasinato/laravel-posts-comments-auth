@extends('layouts.app')
@section('title', 'Nova postagem')
@section('content')
<div class="editor-width">
<a class="back-link" href="{{ route('posts.index') }}">← Todas as postagens</a>
<div class="page-heading">
<p class="eyebrow">SUA VOZ, NOVAS PERSPECTIVAS</p>
<h1>Uma ideia merece<br>ser <em>compartilhada.</em>
</h1>
<p>Escreva sua postagem e abra espaço para a conversa.</p>
</div>
<form class="form-card" action="{{ route('posts.store') }}" method="POST">@csrf @include('posts._form')</form>
</div>
@endsection
