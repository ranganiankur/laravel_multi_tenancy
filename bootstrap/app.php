<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->group('tenant', [
            \App\Http\Middleware\SetTenantFromToken::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\InitializeTenant::class
        ]);

        // 🔐 Sanctum stateful middleware (for SPA or auth sessions)
        // $middleware->statefulApi();
        $middleware->alias([
            'tenant.scope' => \App\Http\Middleware\SetTenantFromToken::class,
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            '2fa' => \PragmaRX\Google2FALaravel\Middleware::class,
            'verify.recaptcha' => \App\Http\Middleware\VerifyRecaptcha::class,

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Illuminate\Http\Exceptions\ThrottleRequestsException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Too many attempts. Please try again later.'
            ], 429);
        });
    })->create();
