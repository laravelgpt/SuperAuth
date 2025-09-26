<?php

namespace SuperAuth\Exceptions;

use Exception;

class FeatureAccessDeniedException extends Exception
{
    public function __construct($message = 'Access denied. Feature not available.', $code = 403, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Feature Access Denied',
                'message' => $this->getMessage(),
                'code' => $this->getCode(),
            ], $this->getCode());
        }

        return response()->view('superauth::errors.feature-access-denied', [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
        ], $this->getCode());
    }
}
