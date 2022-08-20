<?php

namespace UnknownRori\Rin;

use Psr\Container\ContainerInterface;

/**
 * Application Bootstrap of Rin lightweight framework
 */
class Application
{
    public static Configuration $config;
    public ContainerInterface $container;

    /**
     *  Initialize Rin Application bootstrap
     */
    public function __construct(
        Configuration $configuration = new Configuration(),
        ContainerInterface $container = new Container()
        )
    {
        self::$config = $configuration;
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
