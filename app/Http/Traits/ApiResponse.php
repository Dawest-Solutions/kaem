<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\Request;

trait ApiResponse {

    /**
     * Return a success JSON response.
     *
     * @param array|string $data
     * @param string|null $message
     * @param int|null $code
     * @return JsonResponse
     */
    protected function success($data, string $message = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'status' => 'Success',
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Return an error JSON response.
     *
     * @param string|null $message
     * @param int $code
     * @param array|string|null $data
     * @return JsonResponse
     */
    protected function error(string $message = null, int $code = 500, $data = null): JsonResponse
    {
        return response()->json([
            'status' => 'Error',
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
