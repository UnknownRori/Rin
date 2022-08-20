<?php

namespace UnknownRori\Rin;

use Psr\Container\ContainerInterface;

/**
 * Application Bootstrap of Rin light weight framework
 */
class Application
{
    public ?ContainerInterface $container;

    /**
     *  Initialize Rin Application bootstrap
     */
    public function __construct(ContainerInterface $container = new Container())
    {
        $this->container = $container;
    }

    /**
     * Serve the Rin Application
     */
    public function serve(): void
    {
        //
    }

    /**
     * Automatic dependency injection on passed namespace and return the result
     */
    protected function inject($namespace): mixed
    {
        //
    }
}
