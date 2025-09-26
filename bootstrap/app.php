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
        // Register Multi-Vendor Auth middleware
        $middleware->alias([
            'role.access' => \Vendor\MultiVendorAuth\Middleware\RoleBasedAccessMiddleware::class,
            'permission.access' => \Vendor\MultiVendorAuth\Middleware\PermissionBasedAccessMiddleware::class,
            'feature.access' => \Vendor\MultiVendorAuth\Middleware\FeatureAccessMiddleware::class,
            'security.headers' => \Vendor\MultiVendorAuth\Middleware\SecurityHeadersMiddleware::class,
            'rate.limit' => \Vendor\MultiVendorAuth\Middleware\RateLimitMiddleware::class,
            'error.handling' => \Vendor\MultiVendorAuth\Middleware\ErrorHandlingMiddleware::class,
        ]);

        // Apply security headers to all web routes
        $middleware->web(append: [
            \Vendor\MultiVendorAuth\Middleware\SecurityHeadersMiddleware::class,
            \Vendor\MultiVendorAuth\Middleware\ErrorHandlingMiddleware::class,
        ]);

        // Apply rate limiting to authentication routes
        $middleware->group('auth', [
            \Vendor\MultiVendorAuth\Middleware\RateLimitMiddleware::class,
        ]);

        // Apply role-based access to admin routes
        $middleware->group('admin', [
            'role.access:admin,super-admin',
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
        // Handle Multi-Vendor Auth specific exceptions
        $exceptions->render(function (\Vendor\MultiVendorAuth\Exceptions\RoleAccessDeniedException $e) {
            return response()->view('multi-vendor-auth::errors.role-access-denied', [
                'message' => $e->getMessage()
            ], 403);
        });

        $exceptions->render(function (\Vendor\MultiVendorAuth\Exceptions\PermissionAccessDeniedException $e) {
            return response()->view('multi-vendor-auth::errors.permission-access-denied', [
                'message' => $e->getMessage()
            ], 403);
        });

        $exceptions->render(function (\Vendor\MultiVendorAuth\Exceptions\FeatureAccessDeniedException $e) {
            return response()->view('multi-vendor-auth::errors.feature-access-denied', [
                'message' => $e->getMessage()
            ], 403);
        });
    });
