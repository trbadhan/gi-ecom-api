<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof TokenExpiredException) {
            return response()->json([
                'status' => false,
                'code' => 401,
                'message' => 'Token has expired. Please login again.',
            ], 401);
        }

        if ($exception instanceof TokenInvalidException) {
            return response()->json([
                'status' => false,
                'code' => 401,
                'message' => 'Token is invalid.',
            ], 401);
        }

        if ($exception instanceof JWTException) {
            return response()->json([
                'status' => false,
                'code' => 401,
                'message' => 'Token not provided.',
            ], 401);
        }

        if ($exception instanceof UnauthorizedHttpException) {
            return response()->json([
                'status' => false,
                'code' => 401,
                'message' => 'Unauthorized request.',
            ], 401);
        }

        return parent::render($request, $exception);
    }
}
