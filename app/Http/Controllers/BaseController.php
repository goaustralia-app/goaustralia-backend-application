<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    protected function successResponse(string $message, $data = null, int $statusCode = 200): JsonResponse
    {
        $response = [
            'status' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }

    protected function errorResponse(string $message, int $statusCode = 400, $data = null): JsonResponse
    {
        $response = [
            'status' => false,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $statusCode);
    }
}
