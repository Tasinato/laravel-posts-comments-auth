import type { FormEvent } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import AppLayout from '../../Layouts/AppLayout';

type Props = {
    comment: {
        id: number;
        content: string;
    };
    urls: {
        submit: string;
        cancel: string;
    };
};

export default function Edit({ comment, urls }: Props) {
    // Preenche o formulário com o comentário recebido do Laravel.
    const form = useForm({
        content: comment.content,
    });

    function submit(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();

        // Envia a atualização e direciona o foco ao campo com erro.
        form.put(urls.submit, {
            onError: () => {
                document.getElementById('content')?.focus();
            },
        });
    }

    return (
        <AppLayout>
            <Head title="Editar comentário" />

            <section
                className="post-detail"
                aria-labelledby="comment-title"
            >
                <h1 id="comment-title" className="auth-title">
                    Editar comentário
                </h1>

                <form onSubmit={submit} aria-labelledby="comment-title">
                    <div className="form-group">
                        <label htmlFor="content" className="form-label">
                            Comentário
                        </label>

                        <textarea
                            id="content"
                            name="content"
                            className="form-textarea"
                            rows={5}
                            required
                            value={form.data.content}
                            onChange={event =>
                                form.setData('content', event.target.value)
                            }
                            aria-invalid={
                                form.errors.content ? true : undefined
                            }
                            aria-describedby={
                                form.errors.content
                                    ? 'content-error'
                                    : undefined
                            }
                        />

                        {form.errors.content && (
                            <p id="content-error" className="form-error">
                                {form.errors.content}
                            </p>
                        )}
                    </div>

                    <div className="action-group">
                        <button
                            type="submit"
                            className="button button-primary"
                            disabled={form.processing}
                        >
                            {form.processing
                                ? 'Salvando…'
                                : 'Salvar alterações'}
                        </button>

                        <Link
                            href={urls.cancel}
                            className="button button-secondary"
                        >
                            Cancelar
                        </Link>
                    </div>
                </form>
            </section>
        </AppLayout>
    );
}
