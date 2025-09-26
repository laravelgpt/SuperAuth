<?php

namespace SuperAuth\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

class RateLimitMiddleware
{
    public function handle(Request $request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);

        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            
            throw new ThrottleRequestsException(
                'Too many requests. Please try again in ' . $seconds . ' seconds.',
                $seconds
            );
        }

        RateLimiter::hit($key, $decayMinutes * 60);

        $response = $next($request);

        return $response;
    }

    protected function resolveRequestSignature($request)
    {
        if ($user = $request->user()) {
            return 'rate_limit:' . $user->id . ':' . $request->ip();
        }

        return 'rate_limit:' . $request->ip();
    }
}
