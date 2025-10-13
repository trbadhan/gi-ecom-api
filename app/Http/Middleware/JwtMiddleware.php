<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $token = $request->bearerToken() ?? $request->query('signature');
            if (!$token) {
                return response()->json([
                    'status' => false,
                    'message' => 'Token not provided',
                    'data' => null
                ], 401);
            }

            JWTAuth::setToken($token);
            $request->user = JWTAuth::authenticate();
        } catch (TokenExpiredException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Token has expired',
                'data' => null
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Token is Invalid',
                'data' => null
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Token not provided',
                'data' => null
            ], 401);
        }

        return $next($request);
    }
}
