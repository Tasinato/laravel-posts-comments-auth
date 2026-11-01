
@extends('layouts.app')

@section('title', 'Início | Laravel Posts & Comments')

@section('content')
    <section class="welcome-section" aria-labelledby="welcome-title">
        <h1 id="welcome-title">
            Bem-vindo ao Laravel Posts & Comments!
        </h1>

        <p>
            Compartilhe suas ideias, explore postagens
            e participe das conversas.
        </p>

        <p>
            Qualquer pessoa pode visualizar as postagens e os comentários.
            Para publicar ou comentar, basta criar uma conta e entrar.
        </p>

        <div class="welcome-actions">
            <a href="{{ route('posts.index') }}"
               class="button button-primary">
                Explorar postagens
            </a>

            @guest
                <a href="{{ route('register') }}"
                   class="button button-secondary">
                    Criar conta
                </a>
            @endguest

            @auth
                <a href="{{ route('dashboard') }}"
                   class="button button-secondary">
                    Acessar painel
                </a>
            @endauth
        </div>
    </section>
@endsection
