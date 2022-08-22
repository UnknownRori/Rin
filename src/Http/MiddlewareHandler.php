<?php

namespace UnknownRori\Rin\Http;

use Psr\Container\ContainerInterface;
use UnknownRori\Rin\Exceptions\MiddlewareNotFound;
use UnknownRori\Rin\Facades\DependencyInjection;

class MiddlewareHandler
{
    protected array $middleware = [
        'web' => [],
        'api' => [],
        'routeMiddleware' => [],
    ];

    protected ContainerInterface $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    /**
     * Register the list of middleware
     */
    public function Register(array $middleware)
    {
        $this->middleware = array_merge($this->middleware, $middleware);
        return $this;
    }

    /**
     * Run route middleware
     */
    public function run(array |string $middleware, array &$additionalData = []): mixed
    {
        if (is_array($middleware)) {
            $result = [];

            for ($i = 0; $i < count($middleware); $i++) {
                if (array_key_exists($middleware[$i], $this->middleware['routeMiddleware']))
                    $result[] = DependencyInjection::resolveInvoke($this->middleware['routeMiddleware'][$middleware[$i]], $this->container, $additionalData);
                else
                    throw new MiddlewareNotFound($middleware[$i]);
            }

            return $result;
        }

        if (array_key_exists($middleware, $this->middleware['routeMiddleware']))
            return DependencyInjection::resolveInvoke($this->middleware['routeMiddleware'][$middleware], $this->container, $additionalData);

        throw new MiddlewareNotFound($middleware);
    }
}
