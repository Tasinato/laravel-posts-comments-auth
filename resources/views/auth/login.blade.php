@extends('layouts.app')

@section('content')
    <h2>Entrar</h2>

    <form action="{{ route('login') }}" method="POST">
        @csrf

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
            <input id="password" type="password"
                   name="password" required
                   autocomplete="current-password">
            @error('password')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </p>

        <button type="submit">Entrar</button>
    </form>
@endsection