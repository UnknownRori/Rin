<?php

namespace UnknownRori\Rin;

use Psr\Container\ContainerInterface;
use UnknownRori\Rin\Factory\SessionFactory;
use UnknownRori\Rin\Http\{MiddlewareHandler, Request, Route};

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
    ) {
        self::$config = $configuration;
        $this->container = $container;
    }

    /**
     * Serve the Rin Application
     */
    public function serve(): void
    {
        $session = SessionFactory::create(self::$config->sessionDriver, self::$config->sessionConfig);
        $request = new Request($session);
        $middleware = new MiddlewareHandler($this->container);

        Route::serve($this->container, $request, $middleware);
    }
}
