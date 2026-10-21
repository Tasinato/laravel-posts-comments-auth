<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Posts & Comments</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="font-family: Arial, sans-serif; max-width: 650px; margin: 40px auto; padding: 15px;">

    <header>
        <h1>Laravel Posts & Comments</h1>

        <nav>
            <a href="{{ route('home') }}">Início</a>

            @guest
                | <a href="{{ route('login') }}">Entrar</a>
                | <a href="{{ route('register') }}">Cadastrar</a>
            @endguest

            @auth
                | <a href="{{ route('dashboard') }}">Painel</a>

                <form action="{{ route('logout') }}" method="POST"
                      style="display: inline;">
                    @csrf
                    <button type="submit">Sair</button>
                </form>
            @endauth
        </nav>
    </header>

    <hr>

    <main>
        @yield('content')
    </main>

</body>
</html>