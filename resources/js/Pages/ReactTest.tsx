
export default function ReactTest() {
    return (
        <main className="min-h-screen bg-slate-50 p-6">
            <div className="mx-auto max-w-3xl">
                <h1 className="text-3xl font-bold text-slate-900">
                    Laravel + React + Inertia
                </h1>

                <p className="mt-4 text-slate-700">
                    Integração inicial funcionando.
                </p>

                <a
                    href="/posts"
                    className="mt-6 inline-block text-blue-700 underline"
                >
                    Voltar às postagens
                </a>
            </div>
        </main>
    );
}
