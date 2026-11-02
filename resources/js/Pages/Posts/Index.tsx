import { Head, Link, usePage } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';

type Post = {
    id: number;
    title: string;
    content: string;
    author: string;
    created_at: string | null;
    url: string;
};

type PaginationLink = {
    url: string | null;
    label: string;
    active: boolean;
};

type Props = {
    createUrl: string;
    posts: {
        data: Post[];
        links: PaginationLink[];
    };
};

type SharedProps = {
    auth?: {
        user: {
            id: number;
            name: string;
        } | null;
    };
    [key: string]: unknown;
};

export default function Index({ posts, createUrl }: Props) {
    const { auth } = usePage<SharedProps>().props;

    return (
        <AppLayout>
            <Head title="Postagens" />

            <section aria-labelledby="posts-title">
                <h1
                    id="posts-title"
                    className="text-3xl font-bold mb-6"
                >
                    Postagens
                </h1>

                {/* Somente usuários autenticados podem criar postagens. */}
                {auth?.user && (
                    <div className="action-group">
                        <Link
                            href={createUrl}
                            className="button button-primary"
                        >
                            Nova postagem
                        </Link>
                    </div>
                )}

                {posts.data.length === 0 ? (
                    <p>Nenhuma postagem cadastrada.</p>
                ) : (
                    <div className="posts-grid">
                        {posts.data.map(post => (
                            <article
                                key={post.id}
                                className="post-card"
                            >
                                <h2 className="post-card-title">
                                    {post.title}
                                </h2>

                                <p className="post-card-meta">
                                    Por {post.author}
                                    {post.created_at && (
                                        <> · {post.created_at}</>
                                    )}
                                </p>

                                <p className="post-card-excerpt">
                                    {post.content.length > 150
                                        ? `${post.content.slice(0, 150)}…`
                                        : post.content}
                                </p>

                                <Link
                                    href={post.url}
                                    className="post-card-link"
                                    aria-label={`Ler postagem: ${post.title}`}
                                >
                                    Ler postagem
                                </Link>
                            </article>
                        ))}
                    </div>
                )}

                {/* O Laravel fornece os endereços das páginas. */}
                {posts.links?.length > 3 && (
                    <nav
                        aria-label="Paginação de postagens"
                        className="action-group"
                    >
                        {posts.links.map((link, index) => {
                            const label =
                                index === 0
                                    ? 'Anterior'
                                    : index === posts.links.length - 1
                                      ? 'Próxima'
                                      : link.label;

                            return link.url ? (
                                <Link
                                    key={`${label}-${link.url}`}
                                    href={link.url}
                                    className="button button-secondary"
                                    aria-label={`Página ${label}`}
                                    aria-current={
                                        link.active ? 'page' : undefined
                                    }
                                >
                                    {label}
                                </Link>
                            ) : (
                                <span
                                    key={`${label}-${index}`}
                                    aria-disabled="true"
                                >
                                    {label}
                                </span>
                            );
                        })}
                    </nav>
                )}
            </section>
        </AppLayout>
    );
}
