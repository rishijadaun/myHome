<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        $middleware->statefulApi();
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('api/*') || $request->expectsJson()) {
                return null;
            }
            if ($request->is('admin/*')) {
                return route('admin.login');
            }
            if ($request->is('broker/*')) {
                return route('broker.login');
            }
            return route('user.login');
        });
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'broker' => \App\Http\Middleware\BrokerMiddleware::class,
            'security.headers' => \App\Http\Middleware\SecurityHeaders::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated. Please provide a valid Bearer token.',
                    'errors' => [
                        'auth' => ['You are not logged in or your session has expired.']
                    ]
                ], 401);
            }
        });
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, Request $request) {
            if ($request->is('api/*') || $request->expectsJson() || $request->ajax() || $request->isJson()) {
                return response()->json([
                    'success' => false,
                    'csrf_error' => true,
                    'message' => 'Your security session has expired. Please refresh the page and try again.'
                ], 419);
            }
        });
    })->create();
