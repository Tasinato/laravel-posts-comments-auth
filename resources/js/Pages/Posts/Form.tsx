import type { FormEvent } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';

type Props = {
    post?: { id: number; title: string; content: string };
    urls: { submit: string; cancel: string };
};

export default function PostForm({ post, urls }: Props) {
    const form = useForm({ title: post?.title ?? '', content: post?.content ?? '' });
    const heading = post ? 'Editar postagem' : 'Nova postagem';
    function submit(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();
        const options = { onError: (errors: Record<string, string>) => {
            document.getElementById(errors.title ? 'title' : 'content')?.focus();
        } };
        if (post) form.put(urls.submit, options);
        else form.post(urls.submit, options);
    }
    return <AppLayout>
        <Head title={heading} />
        <section className="post-detail" aria-labelledby="post-form-title">
            <h1 id="post-form-title" className="auth-title">{heading}</h1>
            <form onSubmit={submit} aria-labelledby="post-form-title">
                <div className="form-group">
                    <label htmlFor="title" className="form-label">Título</label>
                    <input id="title" name="title" className="form-input" required value={form.data.title}
                        onChange={event => form.setData('title', event.target.value)}
                        aria-invalid={form.errors.title ? true : undefined} aria-describedby={form.errors.title ? 'title-error' : undefined} />
                    {form.errors.title && <p id="title-error" className="form-error">{form.errors.title}</p>}
                </div>
                <div className="form-group">
                    <label htmlFor="content" className="form-label">Conteúdo</label>
                    <textarea id="content" name="content" className="form-textarea" rows={6} required value={form.data.content}
                        onChange={event => form.setData('content', event.target.value)}
                        aria-invalid={form.errors.content ? true : undefined} aria-describedby={form.errors.content ? 'content-error' : undefined} />
                    {form.errors.content && <p id="content-error" className="form-error">{form.errors.content}</p>}
                </div>
                <div className="action-group">
                    <button type="submit" className="button button-primary" disabled={form.processing}>
                        {form.processing ? 'Salvando…' : post ? 'Salvar alterações' : 'Publicar'}
                    </button>
                    <Link href={urls.cancel} className="button button-secondary">Cancelar</Link>
                </div>
            </form>
        </section>
    </AppLayout>;
}
