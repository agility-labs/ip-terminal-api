<?php

namespace App\Http\Controllers;

use App\Enums\ApiResponse\ApiResponseMessagesEnum;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Método para respostas de sucesso.
     *
     * @param  mixed  $data
     * @param  string  $message
     */
    protected function responseSuccess($data, ApiResponseMessagesEnum $message, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    /**
     * Método para respostas de erro.
     *
     * @param  string  $message
     * @param  array  $errors
     */
    protected function responseError(ApiResponseMessagesEnum $message, int $statusCode = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $statusCode);
    }
}
