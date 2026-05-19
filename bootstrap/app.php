<?php

use App\Exceptions\InsufficientStockException;
use App\Exceptions\InvalidOrderStateTransitionException;
use App\Exceptions\OrderNotOwnedByCustomerException;
use App\Http\Middleware\EnsureCustomerActive;
use App\Http\Middleware\EnsureRole;
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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => EnsureRole::class,
            'customer.active' => EnsureCustomerActive::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (InsufficientStockException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 422);
            }

            return back()->withErrors(['stock' => $e->getMessage()]);
        });

        $exceptions->render(function (InvalidOrderStateTransitionException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 422);
            }

            return back()->withErrors(['status' => $e->getMessage()]);
        });

        $exceptions->render(function (OrderNotOwnedByCustomerException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => $e->getMessage()], 403);
            }

            abort(403, $e->getMessage());
        });
    })->create();
