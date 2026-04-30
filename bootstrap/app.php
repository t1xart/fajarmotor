<?php

use App\Exceptions\ApplicationException;
use App\Http\Middleware\EnsureIsAdmin;
use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\LoginRateLimiter;
use App\Http\Middleware\SecurityHeaders;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // CORS untuk Expo app (localhost:8081)
        $middleware->prepend(HandleCors::class);

        // Global middleware — security headers pada semua response
        $middleware->append(SecurityHeaders::class);

        $middleware->alias([
            'active' => EnsureUserIsActive::class,
            'login.limit' => LoginRateLimiter::class,
            'admin' => EnsureIsAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (ApplicationException $e, $request) {
            return $e->render($request);
        });
    })->create();
