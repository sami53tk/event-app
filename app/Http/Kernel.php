<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Les middlewares globaux (appliqués à toutes les requêtes).
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustProxies::class,
        // \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        // etc.
    ];

    /**
     * Les middlewares du groupe 'web'.
     */
    protected $middlewareGroups = [
        'web' => [
            // \App\Http\Middleware\EncryptCookies::class,
            // etc.
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            // etc.
        ],
    ];

    /**
     * Middlewares individuels, invoqués par leur alias.
     */
    protected $routeMiddleware = [
        'role' => \App\Http\Middleware\RoleMiddleware::class,
    ];
}
