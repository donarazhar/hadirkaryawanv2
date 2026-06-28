<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware aliases
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'auth.admin' => \App\Http\Middleware\AuthenticateAdmin::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'no-cache.panel' => \App\Http\Middleware\NoCachePanelMiddleware::class,
        ]);

        $middleware->redirectUsersTo(function (\Illuminate\Http\Request $request) {
            return $request->is('panel*') ? '/panel/dashboard' : '/dashboard';
        });

        // Priority middleware
        $middleware->priority([
            \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \App\Http\Middleware\Authenticate::class,
            \App\Http\Middleware\AuthenticateAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.'
                ], 401);
            }

            $guards = $e->guards();

            if (in_array('user', $guards) || in_array('admin', $guards)) {
                return redirect()->guest(route('panel.login'))
                    ->with('error', 'Silakan login sebagai admin.');
            }

            return redirect()->guest(route('login'))
                ->with('error', 'Silakan login terlebih dahulu.');
        });
    })
    ->create();
