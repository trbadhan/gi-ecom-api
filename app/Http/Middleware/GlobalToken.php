<?php

namespace App\Http\Middleware;

use App\Constants\ApiStatus;
use App\Constants\ErrorResponse;
use App\Traits\ApiResponse;
use Closure;

class GlobalToken
{
    use ApiResponse;

    private $globalToken;

    public function __construct()
    {
        $this->globalToken = config('app.api_sig');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->input('sig') ?? $request->input('sig') ?? $request->query('sig');

        // Check if the token is valid
        if ($token !== $this->globalToken) {
            return $this->errorResponse('Unauthorized', ['exception' => 'Unauthorized signature'], ApiStatus::HTTP_401);
        }

        return $next($request);
    }
}
