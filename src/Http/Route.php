<?php

namespace UnknownRori\Rin\Http;

use Psr\Container\ContainerInterface;

class Route
{
    protected static $route = [
        'GET' => [],
        'POST' => [],
        'PATCH' => [],
        'DELETE' => [],
    ];

    protected static $nameRoute = [];
    protected static $isApi;

    protected $method, $uri, $controller, $middleware, $name, $resource;

    protected static $groupMiddleware, $groupPrefix, $groupName;
    protected static $groupMiddlewareIteration = 0;
    protected static $groupPrefixIteration = 0;
    protected static $groupNameIteration = 0;

    protected static $groupMiddlewareDefiner = false;
    protected static $groupPrefixDefiner = false;
    protected static $groupNameDefiner = false;
    protected static $groupIteration = 0;
    protected static $groupStatus = [];

    /**
     * Starting point of web route class
     * Register all URI to route
     * @param string $configRoute
     */
    public static function defineWeb(string $configRoute): self
    {
        $self = new static;

        require($configRoute);

        return $self;
    }

    /**
     * Starting point of api route class
     * Register all URI to route
     * @param string $configRoute
     */
    public static function defineApi(string $configRoute): self
    {
        $self = new static;

        self::$isApi = true;

        Route::prefix('/api')->group(function () use ($configRoute) {
            require($configRoute);
        });

        return $self;
    }

    public static function serve(ContainerInterface $container, Request $request, MiddlewareHandler $middlewareHandler): void
    {
        $uri = explode('/', $request->getPath());
    }
}
