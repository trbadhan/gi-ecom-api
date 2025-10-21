<?php

namespace App\Http\Middleware;

use App\Constants\ApiStatus;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    protected function redirectTo($request): ?string
    {
        // Prevent Laravel from redirecting (for API requests)
        if ($request->expectsJson() || $request->is('api/*')) {
            abort(response()->json([
                'status'  => false,
                'code'    => ApiStatus::HTTP_401,
                'message' => 'Unauthorized. Please provide a valid token.',
            ], 401));
        }

        // Fallback for web (optional)
        return route('login');
    }

    public function handle($request, Closure $next, ...$guards)
    {
        try {
            $this->authenticate($request, $guards);
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => false,
                'code'    => ApiStatus::HTTP_401,
                'message' => 'Unauthorized access. Token missing or invalid.',
                'errors'  => ['exception' => $e->getMessage()],
            ], 401);
        }

        return $next($request);
    }
}
