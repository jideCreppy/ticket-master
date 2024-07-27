<?php

namespace App\Exceptions\Api\V1;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiExceptions
{
    public static array $handlers = [
        AuthenticationException::class => 'handleAuthenticationException',
        AuthorizationException::class => 'handleAuthorizationException',
        ValidationException::class => 'handleValidationException',
        ModelNotFoundException::class => 'handleNotFoundException',
        NotFoundHttpException::class => 'handleNotFoundException',
    ];

    public static function handleAuthenticationException(AuthenticationException $e, Request $request): JsonResponse
    {
        return response()->json([
            'error' => [
                'type' => class_basename(get_class($e)),
                'status' => 401,
                'message' => $e->getMessage(),
            ],
        ]);
    }

    public static function handleAuthorizationException(AuthorizationException $e, Request $request): JsonResponse
    {
        return response()->json([
            'error' => [
                'type' => class_basename(get_class($e)),
                'status' => 401,
                'message' => $e->getMessage(),
            ],
        ]);
    }

    public static function handleValidationException(ValidationException $e, Request $request): JsonResponse
    {
        $errors = [];

        foreach ($e->errors() as $key => $value) {
            foreach ($value as $message) {
                $errors[] = [
                    'type' => class_basename(get_class($e)),
                    'status' => 422,
                    'message' => $message,
                ];
            }
        }

        return response()->json([
            'errors' => $errors,
        ]);
    }

    public static function handleNotFoundException(ModelNotFoundException|NotFoundHttpException $e, Request $request): JsonResponse
    {
        return response()->json([
            'error' => [
                'type' => class_basename(get_class($e)),
                'status' => 404,
                'message' => 'Not Found '.$request->getRequestUri(),
            ],
        ]);
    }
}
