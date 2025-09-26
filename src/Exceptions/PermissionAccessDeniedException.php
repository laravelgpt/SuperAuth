<?php

namespace SuperAuth\Exceptions;

use Exception;

class PermissionAccessDeniedException extends Exception
{
    public function __construct($message = 'Access denied. Insufficient permissions.', $code = 403, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Permission Access Denied',
                'message' => $this->getMessage(),
                'code' => $this->getCode(),
            ], $this->getCode());
        }

        return response()->view('superauth::errors.permission-access-denied', [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
        ], $this->getCode());
    }
}
