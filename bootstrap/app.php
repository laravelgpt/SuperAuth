<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register SuperAuth middleware
        $middleware->alias([
            'role.access' => \SuperAuth\Middleware\RoleBasedAccessMiddleware::class,
            'permission.access' => \SuperAuth\Middleware\PermissionBasedAccessMiddleware::class,
            'feature.access' => \SuperAuth\Middleware\FeatureAccessMiddleware::class,
            'security.headers' => \SuperAuth\Middleware\SecurityHeadersMiddleware::class,
            'rate.limit' => \SuperAuth\Middleware\RateLimitMiddleware::class,
            'error.handling' => \SuperAuth\Middleware\ErrorHandlingMiddleware::class,
        ]);

        // Apply security headers to all web routes
        $middleware->web(append: [
            \SuperAuth\Middleware\SecurityHeadersMiddleware::class,
            \SuperAuth\Middleware\ErrorHandlingMiddleware::class,
        ]);

        // Apply rate limiting to authentication routes
        $middleware->group('auth', [
            \SuperAuth\Middleware\RateLimitMiddleware::class,
        ]);

        // Apply role-based access to admin routes
        $middleware->group('admin', [
            'role.access:admin,super_admin',
        ]);

        // Apply permission-based access to specific routes
        $middleware->group('user-management', [
            'permission.access:manage-users',
        ]);

        // Apply feature-based access to specific features
        $middleware->group('dashboard', [
            'feature.access:admin-dashboard',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Handle SuperAuth specific exceptions
        $exceptions->render(function (\SuperAuth\Exceptions\RoleAccessDeniedException $e) {
            return response()->view('superauth::errors.role-access-denied', [
                'message' => $e->getMessage()
            ], 403);
        });

        $exceptions->render(function (\SuperAuth\Exceptions\PermissionAccessDeniedException $e) {
            return response()->view('superauth::errors.permission-access-denied', [
                'message' => $e->getMessage()
            ], 403);
        });

        $exceptions->render(function (\SuperAuth\Exceptions\FeatureAccessDeniedException $e) {
            return response()->view('superauth::errors.feature-access-denied', [
                'message' => $e->getMessage()
            ], 403);
        });
    });
