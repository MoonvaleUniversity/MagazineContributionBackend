<?php

if (!function_exists('apiResponse')) {
    function apiResponse(bool $success, string $message = '', $data = null, int $statusCode = 200, array $errors = [])
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data'    => $data,
            'errors'  => $errors,
            'meta'    => [
                'timestamp' => now()->toISOString(),
                'status'    => $statusCode
            ]
        ], $statusCode);
    }
}
