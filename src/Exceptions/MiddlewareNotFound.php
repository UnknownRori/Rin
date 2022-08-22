<?php

namespace UnknownRori\Rin\Exceptions;

use Exception;

/**
 * Middleware not found
 */
class MiddlewareNotFound extends Exception
{
    public function __construct(string $middleware = '')
    {
        $this->message = "Middleware not found! Middleware : {$middleware}";
    }
}
