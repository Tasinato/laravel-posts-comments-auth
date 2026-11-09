<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#204d42">
<title>@yield('title', 'Um espaço para boas conversas') · Posts & Comments</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<a class="skip-link" href="#conteudo">Pular para o conteúdo</a>
<header class="site-header">
<div class="header-inner">
<a class="brand" href="{{ route('home') }}" aria-label="Posts & Comments — início">
<span class="brand-mark" aria-hidden="true">p<span>c</span>
</span>
<span>posts<span class="brand-amp"> & </span>comments<span class="brand-dot">.</span>
</span>
</a>
<nav class="main-nav" aria-label="Navegação principal">
<a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}" @if(request()->routeIs('home')) aria-current="page" @endif>Início</a>
<a class="nav-link {{ request()->routeIs('posts.*') ? 'active' : '' }}" href="{{ route('posts.index') }}">Postagens</a>
                @auth <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Meu painel</a> @endauth
            </nav>
<div class="account-nav">
                @guest
                    <a class="login-link" href="{{ route('login') }}">Entrar</a>
<a class="button button-small" href="{{ route('register') }}">Criar conta <span aria-hidden="true">↗</span>
</a>
                @else
                    <span class="user-name">{{ Str::before(auth()->user()->name, ' ') }}</span>
<form action="{{ route('logout') }}" method="POST">@csrf <button class="button button-small button-outline" type="submit">Sair</button>
</form>
                @endguest
            </div>
</div>
</header>
<main id="conteudo" class="site-main" tabindex="-1">
        @if(session('success')) <div class="notice notice-success" role="status">
<strong>Pronto!</strong> {{ session('success') }}</div> @endif
        @yield('content')
    </main>
<footer class="site-footer">
<div>
<a class="footer-brand" href="{{ route('home') }}">posts & comments.</a>
<span>Um espaço para compartilhar e conversar.</span>
</div>
<p>Desenvolvido por <a href="https://github.com/Tasinato">Rafael Tasinato <span aria-hidden="true">↗</span>
</a>
</p>
</footer>
</body>
</html>
