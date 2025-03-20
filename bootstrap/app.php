<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

/*
|--------------------------------------------------------------------------
| Créer l'instance de l'application
|--------------------------------------------------------------------------
|
| On configure l'app via la “fluent API” puis on termine par ->create().
| On récupère l’instance dans une variable $app AVANT le return.
|
*/

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middlewares globaux éventuels
        // $middleware->push(\App\Http\Middleware\TrustProxies::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Config exceptions si besoin
    })
    ->create();

/*
|--------------------------------------------------------------------------
| Enregistrer le Kernel HTTP (et éventuellement le Kernel console)
|--------------------------------------------------------------------------
|
| On indique à l'application quel Kernel utiliser pour gérer les requêtes.
| Ici, on injecte votre classe App\Http\Kernel.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    \App\Http\Kernel::class
);

// De même, si vous avez un Kernel console, on ferait :
// $app->singleton(
//     Illuminate\Contracts\Console\Kernel::class,
//     App\Console\Kernel::class
// );

/*
|--------------------------------------------------------------------------
| Retourner l'application configurée
|--------------------------------------------------------------------------
*/
return $app;
