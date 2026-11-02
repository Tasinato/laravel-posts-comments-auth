import type { FormEvent } from 'react';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';

type Resource = {
    id: number;
    content: string;
    author: string;
    can: { update: boolean; delete: boolean };
    urls: { edit: string; destroy: string };
};

type Props = {
    post: Resource & { title: string; comments: Resource[] };
    urls: { index: string; login: string; commentStore: string };
};

type SharedProps = {
    auth?: { user: { id: number; name: string } | null };
    [key: string]: unknown;
};

function Actions({ resource, label }: { resource: Resource; label: string }) {
    const deletion = useForm({});
    function remove() {
        if (window.confirm('Excluir este conteúdo?')) {
            deletion.delete(resource.urls.destroy, { preserveScroll: true });
        }
    }
    return <>
        {resource.can.update && <Link href={resource.urls.edit} className="button button-secondary">Editar {label}</Link>}
        {resource.can.delete && <button type="button" className="button button-secondary" disabled={deletion.processing} onClick={remove}>Excluir {label}</button>}
    </>;
}

export default function Show({ post, urls }: Props) {
    const { auth } = usePage<SharedProps>().props;
    const form = useForm({ content: '' });
    function submitComment(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();
        form.post(urls.commentStore, {
            preserveScroll: true,
            onSuccess: () => form.reset('content'),
            onError: () => document.getElementById('content')?.focus(),
        });
    }

    return (
        <AppLayout>
            <Head title={`${post.title} — Laravel Posts & Comments`} />

            <article className="post-detail">
                <h1 className="post-detail-title">{post.title}</h1>
                <p className="post-detail-meta">Autor: {post.author}</p>
                <div className="post-detail-content">{post.content}</div>
            </article>

            <div className="post-detail-actions">
                <Actions resource={post} label="postagem" />
                <Link href={urls.index} className="button button-secondary">Voltar às postagens</Link>
            </div>

            <section className="comments-section" aria-labelledby="comments-title">
                <h2 id="comments-title">Comentários</h2>
                {post.comments.length === 0 && <p>Esta postagem ainda não possui comentários.</p>}
                {post.comments.map(comment => (
                    <article className="comment-card" key={comment.id}>
                        <h3 className="comment-author">{comment.author}</h3>
                        <p className="comment-content">{comment.content}</p>
                        {(comment.can.update || comment.can.delete) && (
                            <div className="action-group">
                                <Actions resource={comment} label="comentário" />
                            </div>
                        )}
                    </article>
                ))}

                {auth?.user ? (
                    <>
                        <h2 id="comment-form-title">Adicionar comentário</h2>
                        <form onSubmit={submitComment} aria-labelledby="comment-form-title">
                            <div className="form-group">
                                <label htmlFor="content" className="form-label">Comentário</label>
                                <textarea id="content" name="content" rows={4} className="form-textarea" required
                                    value={form.data.content} onChange={event => form.setData('content', event.target.value)}
                                    aria-invalid={form.errors.content ? true : undefined}
                                    aria-describedby={form.errors.content ? 'content-error' : undefined} />
                                {form.errors.content && <p id="content-error" className="form-error">{form.errors.content}</p>}
                            </div>
                            <button type="submit" className="button button-primary" disabled={form.processing}>{form.processing ? 'Enviando…' : 'Comentar'}</button>
                        </form>
                    </>
                ) : (
                    <p><a href={urls.login} className="button button-secondary">Entre</a> para deixar um comentário.</p>
                )}
            </section>
        </AppLayout>
    );
}
