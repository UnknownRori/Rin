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

    // array<\UnknownRori\Rin\Contracts\Factory, array>
    public array $factory = [
        'session' => SessionFactory::class,
    ];

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
     * Register a custom factory to the application
     * @param  string $namespace - must implement \UnknownRori\Contracts\Factory
     */
    public function registerFactory(string $name, string $namespace)
    {
        $this->factory[$name] = $namespace;
    }

    /**
     * Serve the Rin Application
     */
    public function serve()
    {
        $session = $this->factory['session']::create(self::$config->sessionDriver, self::$config->sessionConfig);
        $request = new Request($session);
        $middleware = new MiddlewareHandler($this->container);

        $allowedResource = implode('/', Application::$config->allowedResource);

        if (preg_match("/\.(?:{$allowedResource})$/", $request->getPath())) return false;

        else Route::serve($this->container, $request, $middleware);
    }
}
