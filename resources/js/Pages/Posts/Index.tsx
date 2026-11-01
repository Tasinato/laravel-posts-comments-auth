
import { Head } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';

type Post = {
    id: number;
    title: string;
    content: string;
    author: string;
    created_at: string | null;
    url: string;
};

type Props = {
    readonly posts: {
        readonly data: readonly Post[];
    };
};

export default function Index({ posts }: Props) {
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

                {posts.data.length === 0 ? (
                    <p>Nenhuma postagem cadastrada.</p>
                ) : (
                    <div className="posts-grid">
                        {posts.data.map((post) => (
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

                                <a
                                    href={post.url}
                                    className="post-card-link"
                                    aria-label={`Ler postagem: ${post.title}`}
                                >
                                    Ler postagem
                                </a>
                            </article>
                        ))}
                    </div>
                )}
            </section>
        </AppLayout>
    );
}
