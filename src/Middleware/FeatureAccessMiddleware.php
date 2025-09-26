<?php

namespace SuperAuth\Middleware;

use Closure;
use Illuminate\Http\Request;
use SuperAuth\Exceptions\FeatureAccessDeniedException;

class FeatureAccessMiddleware
{
    public function handle(Request $request, Closure $next, $feature)
    {
        if (!auth()->check()) {
            return redirect()->route('superauth.login');
        }

        $user = auth()->user();

        if (!$user->hasFeatureAccess($feature)) {
            throw new FeatureAccessDeniedException('Access denied. Required feature: ' . $feature);
        }

        return $next($request);
    }
}
