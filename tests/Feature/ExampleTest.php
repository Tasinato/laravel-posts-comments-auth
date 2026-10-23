<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Verifica se a página inicial responde com HTTP 200.
     *
     * A integração com o Vite é desativada porque este teste
     * não tem como objetivo validar os assets CSS e JavaScript.
     *
     * A rota e a renderização da view Blade continuam sendo
     * executadas normalmente.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->withoutVite();

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
