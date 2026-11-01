@extends('layouts.app')

@section('title', 'Painel — Laravel Posts & Comments')

@section('content')
    <h1 class="auth-title">Painel do usuário</h1>

    <p>Olá, {{ auth()->user()->name }}!</p>

    <p>Você está autenticado no sistema.</p>

    <p>E-mail: {{ auth()->user()->email }}</p>
@endsection