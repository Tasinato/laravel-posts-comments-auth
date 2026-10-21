@extends('layouts.app')

@section('content')
    <h2>Criar conta</h2>

    <form action="{{ route('register') }}" method="POST">
        @csrf

        <p>
            <label for="name">Nome</label><br>
            <input id="name" type="text" name="name"
                   value="{{ old('name') }}" required
                   autocomplete="name">
            @error('name')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </p>

        <p>
            <label for="email">E-mail</label><br>
            <input id="email" type="email" name="email"
                   value="{{ old('email') }}" required
                   autocomplete="email">
            @error('email')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </p>

        <p>
            <label for="password">Senha</label><br>
            <input id="password" type="password" name="password"
                   required autocomplete="new-password">
            @error('password')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </p>

        <p>
            <label for="password_confirmation">Confirmar senha</label><br>
            <input id="password_confirmation" type="password"
                   name="password_confirmation" required
                   autocomplete="new-password">
        </p>

        <button type="submit">Cadastrar</button>
    </form>
@endsection