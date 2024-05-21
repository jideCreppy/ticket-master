<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponses
{

    protected function ok($message, $data = [], $statusCode = 200): JsonResponse
    {
        return $this->success($message, $data, $statusCode);
    }

    protected function success($message, $data = [], $statusCode = 200): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
            'status' => $statusCode
        ], $statusCode);
    }

}
