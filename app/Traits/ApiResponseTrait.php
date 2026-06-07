<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    // Standard success envelope used by all API endpoints.
    public function successResponse(mixed $data = [], string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'true',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    // Standard error envelope used by all API endpoints.
    public function errorResponse(string $message = 'Error', int $code = 400): JsonResponse
    {
        return response()->json([
            'status' => 'false',
            'message' => $message,
        ], $code);
    }
}
