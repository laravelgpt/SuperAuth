<?php

namespace SuperAuth\Middleware;

use Closure;
use Illuminate\Http\Request;
use SuperAuth\Exceptions\RoleAccessDeniedException;

class RoleBasedAccessMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect()->route('superauth.login');
        }

        $user = auth()->user();

        if (empty($roles)) {
            return $next($request);
        }

        if (!$user->hasAnyRole($roles)) {
            throw new RoleAccessDeniedException('Access denied. Required role: ' . implode(', ', $roles));
        }

        return $next($request);
    }
}
