<?php

namespace SuperAuth\Middleware;

use Closure;
use Illuminate\Http\Request;
use SuperAuth\Exceptions\PermissionAccessDeniedException;

class PermissionBasedAccessMiddleware
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        if (!auth()->check()) {
            return redirect()->route('superauth.login');
        }

        $user = auth()->user();

        if (empty($permissions)) {
            return $next($request);
        }

        if (!$user->hasAnyPermission($permissions)) {
            throw new PermissionAccessDeniedException('Access denied. Required permission: ' . implode(', ', $permissions));
        }

        return $next($request);
    }
}
