<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Configuration pour les tests
        $this->app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
        $this->app['config']->set('app.debug', true);

        // Utiliser SQLite en mémoire pour les tests
        $this->app['config']->set('database.default', 'sqlite');
        $this->app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        // Désactiver la vérification CSRF pour les tests
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class);

        // Désactiver les notifications par email pendant les tests
        $this->app['config']->set('mail.default', 'array');
    }
}
