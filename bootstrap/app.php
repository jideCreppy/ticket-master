<?php

use App\Exceptions\Api\V1\ApiExceptions;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('api')
                ->prefix('api/V1')
                ->group(base_path('routes/V1/api.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: ['*']);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(using: function (Throwable $e, Request $request) {
            $className = get_class($e);
            $handlers = ApiExceptions::$handlers;

            if (array_key_exists($className, $handlers)) {
                $method = $handlers[$className];

                return ApiExceptions::$method($e, $request);
            }

            return response()->json([
                'error' => [
                    'type' => class_basename(get_class($e)),
                    'status' => intval($e->getCode()), // returns 0 if no code
                    'message' => $e->getMessage(),
                ],
            ]);
        });
    })->create();
