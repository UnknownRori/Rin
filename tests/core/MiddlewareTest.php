<?php

namespace UnknownRori\Rin\Tests\Core;

use UnknownRori\Rin\Container;
use PHPUnit\Framework\TestCase;
use UnknownRori\Rin\Http\MiddlewareHandler;

class Authentication
{
    public function __invoke()
    {
        return "Done!";
    }
}

class Sum
{
    public function __invoke(int $a, int $b)
    {
        return $a + $b;
    }
}

class DoSomething
{
    public function __invoke(Authentication $auth)
    {
        return $auth();
    }
}

/**
 * @covers \UnknownRori\Http\Middleware
 */
class MiddlewareTests extends TestCase
{

    /**
     * @test
     */
    public function simple_middleware()
    {
        $container = new Container();
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
        $container = new Container();
        $container->set('a', 1);
        $container->set('b', 2);

        $middleware = [
            'routeMiddleware' => [
                'sum' => Sum::class
            ]
        ];

        $handler = (new MiddlewareHandler($container))->register($middleware);
        $result = $handler->run('sum');

        $this->assertEquals(3, $result, "it should return 3");
    }

    /**
     * @test
     */
    public function simple_dependency_inject_2()
    {
        $container = new Container();

        $middleware = [
            'routeMiddleware' => [
                'sum' => Sum::class
            ]
        ];

        $handler = (new MiddlewareHandler($container))->register($middleware);
        $result = $handler->run('sum', ['a' => 1, 'b' => 2]);

        $this->assertEquals(3, $result, "it should return 3");
    }

    /**
     * @test
     */
    public function simple_dependency_inject_3()
    {
        $container = new Container();
        $container->set(Authentication::class , new Authentication());

        $middleware = [
            'routeMiddleware' => [
                'doSomething' => DoSomething::class
            ]
        ];

        $handler = (new MiddlewareHandler($container))->register($middleware);
        $result = $handler->run('doSomething');

        $this->assertEquals('Done!', $result, "it should return {Done!}");
    }
}