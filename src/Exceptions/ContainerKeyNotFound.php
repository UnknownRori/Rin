<?php

namespace UnknownRori\Rin\Exceptions;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Container Not Found Data
 */
class ContainerKeyNotFound extends Exception implements NotFoundExceptionInterface
{
    public function __construct($message = 'Container key not found, no data can be retrieved')
    {
        $this->message = $message;
    }
}
