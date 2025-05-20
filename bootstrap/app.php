<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Asegúrate de importar tu middleware
use App\Http\Middleware\CheckSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Aquí puedes agregar middlewares globales si lo deseas, como StartSession, etc.
        
        // Registrar alias para usarlo en rutas
        $middleware->alias([
            'session.auth' => CheckSession::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
