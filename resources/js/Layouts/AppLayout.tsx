
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
    [key: string]: unknown;
};

type AppLayoutProps = {
    readonly children: ReactNode;
};

export default function AppLayout({ children }: AppLayoutProps) {
    const { auth } = usePage<SharedProps>().props;
    const user = auth?.user ?? null;

    function handleLogout() {
        router.post('/logout');
    }

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
                        <a href="/posts">Postagens</a>

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
                {children}
            </main>
        </>
    );
}
