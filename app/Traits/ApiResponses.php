<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponses
{
    /**
     * @param  array<non-empty-string, mixed>  $data
     */
    protected function ok(string $message, array $data = [], int $statusCode = 200): JsonResponse
    {
        return $this->success($message, $data, $statusCode);
    }

    /**
     * @param  array<non-empty-string, mixed>  $data
     */
    protected function success(string $message, array $data = [], int $statusCode = 200): JsonResponse
    {
        return response()->json(
            [
                'message' => $message,
                'status' => $statusCode,
                'data' => $data,
            ], $statusCode
        );
    }
}
