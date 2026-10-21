@extends('layouts.app')

@section('content')
    <h2>Painel do usuário</h2>

    <p>Olá, {{ auth()->user()->name }}!</p>

    <p>Você está autenticado no sistema.</p>

    <p>
        E-mail: {{ auth()->user()->email }}
    </p>
@endsection