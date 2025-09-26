<?php

namespace SuperAuth\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use SuperAuth\Services\ErrorReportingService;

class ErrorHandlingMiddleware
{
    protected $errorReportingService;

    public function __construct(ErrorReportingService $errorReportingService)
    {
        $this->errorReportingService = $errorReportingService;
    }

    public function handle(Request $request, Closure $next)
    {
        try {
            $response = $next($request);
            
            // Log successful requests if needed
            if (config('superauth.logging.log_successful_requests', false)) {
                $this->logRequest($request, $response);
            }
            
            return $response;
        } catch (\Exception $e) {
            return $this->handleException($request, $e);
        }
    }

    protected function handleException(Request $request, \Exception $e)
    {
        $errorData = [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => auth()->id(),
            'timestamp' => now()->toISOString(),
        ];

        // Log the error
        Log::error('Application Error', $errorData);

        // Report to error reporting service
        $this->errorReportingService->reportError($errorData);

        // Return appropriate response
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Internal Server Error',
                'message' => config('app.debug') ? $e->getMessage() : 'Something went wrong',
            ], 500);
        }

        return response()->view('superauth::errors.500', [
            'error' => $e,
            'debug' => config('app.debug'),
        ], 500);
    }

    protected function logRequest(Request $request, $response)
    {
        Log::info('Request processed', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'status' => $response->getStatusCode(),
            'ip' => $request->ip(),
            'user_id' => auth()->id(),
        ]);
    }
}
