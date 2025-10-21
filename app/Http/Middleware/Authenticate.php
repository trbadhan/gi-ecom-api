<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request)
    {
        // If the request expects JSON (API request), return null to avoid redirect
        if ($request->expectsJson()) {
            return null;
        }

        // Otherwise fallback to web login route
        return route('login');
    }
}
