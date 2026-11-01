<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Laravel Posts & Comments')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <a class="skip-link" href="#conteudo-principal">
        Pular para o conteúdo principal
    </a>

    <header class="site-header">
        <div class="site-container header-content">
            <a class="site-brand" href="{{ route('home') }}">
                Laravel Posts & Comments
            </a>

            <nav class="site-nav" aria-label="Navegação principal">
                <a href="{{ route('home') }}">Início</a>
                <a href="{{ route('posts.index') }}">Postagens</a>

                @guest
                    <a href="{{ route('login') }}">Entrar</a>
                    <a href="{{ route('register') }}">Cadastrar</a>
                @endguest

                @auth
                    {{-- Identificação do usuário autenticado --}}
                    <span class="nav-user">
                        Olá, {{ auth()->user()->name }}
                    </span>

                    {{-- Acesso ao painel --}}
                    <a href="{{ route('dashboard') }}">
                        Painel
                    </a>

                    {{-- Encerramento seguro da sessão --}}
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf

                        <button type="submit" class="nav-logout">
                            Sair
                        </button>
                    </form>
                @endauth
            </nav>
        </div>
    </header>

    <main id="conteudo-principal" class="site-container main-content">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
