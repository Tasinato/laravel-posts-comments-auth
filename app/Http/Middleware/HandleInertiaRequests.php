<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * Template inicial das páginas React/Inertia.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Dados disponíveis nas páginas por usePage().props.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            // Preserva os dados padrão do Inertia,
            // incluindo os erros de validação.
            ...parent::share($request),

            // Mensagens temporárias após as operações.
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],

            // Expõe somente os dados necessários do usuário.
            'auth' => [
                'user' => fn () => $request->user()
                    ? [
                        'id' => $request->user()->id,
                        'name' => $request->user()->name,
                    ]
                    : null,
            ],
        ];
    }
}
