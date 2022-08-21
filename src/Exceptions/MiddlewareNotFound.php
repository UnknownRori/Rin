<?php

namespace UnknownRori\Rin\Exceptions;

use Exception;
/**
 * Container Not Found Data
 */
class MiddlewareNotFound extends Exception
{
    public function __construct(string $middleware = '')
    {
        $this->message = "Middleware not found! Middleware : {$middleware}";
    }
}
