<?php

namespace UnknownRori\Rin\Http;

use Psr\Container\ContainerInterface;
use UnknownRori\Rin\Services\ResolveDependency;
use UnknownRori\Rin\Exceptions\MiddlewareNotFound;

class Middleware
{
    protected static array $middleware = [
        'web' => [],
        'api' => [],
        'routeMiddleware' => [],
    ];

    /**
     * Register the list of middleware
     */
    public static function Register(array $middleware)
    {
        self::$middleware = array_merge(self::$middleware, $middleware);
    }

    /**
     * Run route middleware
     */
    public static function run(array |string $middleware, ContainerInterface $container, array $additionalData = []): mixed
    {
        if (is_array($middleware)) {
            $result = [];

            for ($i = 0; $i < count($middleware); $i++) {
                if (array_key_exists($middleware[$i], self::$middleware['routeMiddleware']))
                    $result[] = ResolveDependency::resolveInvoke(self::$middleware['routeMiddleware'][$middleware[$i]], $container, $additionalData);
                else
                    return throw new MiddlewareNotFound($middleware[$i]);
            }

            return $result;
        }

        if (array_key_exists($middleware, self::$middleware['routeMiddleware']))
            return ResolveDependency::resolveInvoke(self::$middleware['routeMiddleware'][$middleware], $container, $additionalData);

        return new MiddlewareNotFound($middleware);
    }
}