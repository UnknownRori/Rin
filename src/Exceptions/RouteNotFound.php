<?php

namespace UnknownRori\Rin\Exceptions;

use Exception;

/**
 * Route not found
 */
class RouteNotFound extends Exception
{
    public function __construct()
    {
        $this->message = "Route not found!";
    }
}
