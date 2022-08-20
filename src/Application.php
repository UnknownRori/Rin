<?php

namespace UnknownRori\ProjectRin;

use Psr\Container\ContainerInterface;

/**
 * Application Bootstrap of Project Reiki light weight framework
 */
class Application
{
    public ?ContainerInterface $container;

    /**
     *  Initialize Project Reiki Application bootstrap
     */
    public function __construct(ContainerInterface $container = new Container())
    {
        $this->container = $container;
    }

    /**
     * Serve the Project Reiki Application
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
