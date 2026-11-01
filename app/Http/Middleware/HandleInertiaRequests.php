<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

// Personaliza o middleware padrão do Inertia.
class HandleInertiaRequests extends Middleware
{
    /**
     * Define resources/views/app.blade.php como o template
     * HTML inicial das páginas React renderizadas pelo Inertia.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Compartilha dados com as páginas Inertia.
     * No React, esses dados podem ser acessados com usePage().props.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            // Preserva os dados compartilhados pelo middleware base.
            ...parent::share($request),

            // Agrupa as informações de autenticação.
            'auth' => [
                // A função permite avaliar o usuário quando o Inertia
                // resolver esta prop. Retorna null para visitantes.
                'user' => fn () => $request->user()
                    ? [
                        // Expõe apenas os dados necessários ao frontend.
                        'id' => $request->user()->id,
                        'name' => $request->user()->name,
                    ]
                    : null,
            ],
        ];
    }
}
