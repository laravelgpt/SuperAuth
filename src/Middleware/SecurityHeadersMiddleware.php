<?php

namespace SuperAuth\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SecurityHeadersMiddleware
{
    protected $headers = [
        'X-Content-Type-Options' => 'nosniff',
        'X-Frame-Options' => 'DENY',
        'X-XSS-Protection' => '1; mode=block',
        'Referrer-Policy' => 'strict-origin-when-cross-origin',
        'Permissions-Policy' => 'geolocation=(), microphone=(), camera=()',
        'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
    ];

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response instanceof Response) {
            $this->addSecurityHeaders($response);
        }

        return $response;
    }

    protected function addSecurityHeaders(Response $response)
    {
        foreach ($this->headers as $header => $value) {
            $response->headers->set($header, $value);
        }

        // Add Content Security Policy
        $this->addContentSecurityPolicy($response);
    }

    protected function addContentSecurityPolicy(Response $response)
    {
        $csp = $this->buildContentSecurityPolicy();
        $response->headers->set('Content-Security-Policy', $csp);
    }

    protected function buildContentSecurityPolicy()
    {
        $directives = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.tailwindcss.com https://cdn.jsdelivr.net",
            "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://cdn.tailwindcss.com",
            "font-src 'self' https://fonts.bunny.net https://fonts.gstatic.com",
            "img-src 'self' data: https: blob:",
            "connect-src 'self' https://api.pwnedpasswords.com",
            "frame-ancestors 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "upgrade-insecure-requests",
        ];

        return implode('; ', $directives);
    }
}
