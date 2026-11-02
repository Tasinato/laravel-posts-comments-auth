import type { ReactNode } from 'react';
import { router, usePage } from '@inertiajs/react';

type AuthUser = {
    id: number;
    name: string;
};

type SharedProps = {
    auth?: {
        user: AuthUser | null;
    };
    flash?: {
        success?: string | null;
        error?: string | null;
    };
    [key: string]: unknown;
};

type AppLayoutProps = {
    readonly children: ReactNode;
};

function handleLogout() {
    router.post('/logout');
}

export default function AppLayout({ children }: AppLayoutProps) {
    // Dados compartilhados pelo HandleInertiaRequests.
    const { auth, flash } = usePage<SharedProps>().props;
    const user = auth?.user ?? null;

    return (
        <>
            <a href="#conteudo-principal" className="skip-link">
                Pular para o conteúdo principal
            </a>

            <header className="site-header">
                <div className="site-container header-content">
                    <a href="/" className="site-brand">
                        Laravel Posts & Comments
                    </a>

                    <nav
                        className="site-nav"
                        aria-label="Navegação principal"
                    >
                        <a href="/">Início</a>
                        <a href="/react-posts-test">Postagens</a>

                        {user ? (
                            <>
                                <span className="nav-user">
                                    Olá, {user.name}
                                </span>

                                <a href="/dashboard">Painel</a>

                                <button
                                    type="button"
                                    className="nav-logout"
                                    onClick={handleLogout}
                                >
                                    Sair
                                </button>
                            </>
                        ) : (
                            <>
                                <a href="/login">Entrar</a>
                                <a href="/register">Cadastrar</a>
                            </>
                        )}
                    </nav>
                </div>
            </header>

            <main
                id="conteudo-principal"
                className="site-container main-content"
                tabIndex={-1}
            >
                {/* Confirmação da operação enviada pelo Laravel. */}
                {flash?.success && (
                    <output className="alert alert-success">
                        {flash.success}
                    </output>
                )}

                {/* Mensagem geral de erro enviada pelo Laravel. */}
                {flash?.error && (
                    <div className="alert alert-error" role="alert">
                        {flash.error}
                    </div>
                )}

                {children}
            </main>
        </>
    );
}
