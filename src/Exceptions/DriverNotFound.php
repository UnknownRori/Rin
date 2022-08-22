<?php

namespace UnknownRori\Rin\Exceptions;

use Exception;

class DriverNotFound extends Exception
{
    public function __construct(string $driverName)
    {
        $this->message = "Driver {$driverName} not found!";
    }
}
