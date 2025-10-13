<?php

namespace App\Traits;

use App\Constants\ApiStatus;
use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Success Response
     */
    protected function successResponse($data = [], string $message = 'Success', int $code = ApiStatus::HTTP_200, $pagination = null): JsonResponse
    {
        return response()->json([
            'status'  => ApiStatus::CONST_TRUE,
            'code'    => $code,
            'message' => $message,
            'data'    => $data,
            'pagination' => isset($pagination) ? $pagination : null,
        ], $code);
    }

    /**
     * Error Response (unexpected errors, exceptions)
     */
    protected function errorResponse(string $message = 'Something went wrong', $errors = [], int $code = ApiStatus::HTTP_500): JsonResponse
    {
        return response()->json([
            'status'  => false,
            'code'    => $code,
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }

    /**
     * Failed Response (validation / user mistakes)
     */
    protected function failedResponse(string $message = 'Validation failed', $errors = [], int $code = ApiStatus::HTTP_422): JsonResponse
    {
        return response()->json([
            'status'  => false,
            'code'    => $code,
            'message' => $message,
            'errors'  => $errors,
        ], $code);
    }
}
