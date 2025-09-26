<?php

namespace SuperAuth\Exceptions;

use Exception;

class RoleAccessDeniedException extends Exception
{
    public function __construct($message = 'Access denied. Insufficient role permissions.', $code = 403, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Role Access Denied',
                'message' => $this->getMessage(),
                'code' => $this->getCode(),
            ], $this->getCode());
        }

        return response()->view('superauth::errors.role-access-denied', [
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
        ], $this->getCode());
    }
}
