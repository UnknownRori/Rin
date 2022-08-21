<?php

namespace UnknownRori\Rin\Tests\Symfony;

use UnknownRori\Rin\Http\MiddlewareHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class Authentication
{
    public function __invoke()
    {
        return "Done!";
    }
}

class Sum
{
    public function __invoke(Authentication $auth)
    {
        return $auth();
    }
}

/**
 * @covers \UnknownRori\Http\Middleware
 */
class CoreMiddlewareTests extends TestCase
{

    /**
     * @test
     */
    public function simple_middleware()
    {
        $container = new ContainerBuilder();
        $middleware = [
            'routeMiddleware' => [
                'auth' => Authentication::class
            ]
        ];

        $handler = (new MiddlewareHandler($container))->register($middleware);
        $result = $handler->run('auth');

        $this->assertEquals('Done!', $result, 'It should prinnt {Done!}');
    }

    /**
     * @test
     */
    public function simple_dependency_inject()
    {
        $container = new ContainerBuilder();
        $container->register(Authentication::class , Authentication::class);

        $middleware = [
            'routeMiddleware' => [
                'sum' => Sum::class
            ]
        ];

        $handler = (new MiddlewareHandler($container))->register($middleware);
        $result = $handler->run('sum');

        $this->assertEquals("Done!", $result, "it should return {Done!}");
    }
}