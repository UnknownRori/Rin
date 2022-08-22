<?php

namespace UnknownRori\Rin\Http;

use Exception;
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

    protected $method, $methods, $uri, $controller, $middleware, $name, $resource;

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
     * Register the URI on route destruct
     * @return void
     */
    public function __destruct()
    {
        // if (isset($this->methods)) {
        //     array_multisort($this->methods);

        //     $this->registerResource();
        // } else
        if (isset($this->method)) {

            $this->registerPrefix();

            if (!isset(self::$route[$this->method][$this->uri])) {
                self::$route[$this->method][$this->uri] = ['action' => $this->controller];

                $this->registerMiddleware();
            } else return throw new Exception("Route already defined!");

            $this->registerName();
        }
    }

    public static function serve(ContainerInterface $container, Request $request, MiddlewareHandler $middlewareHandler): void
    {
        $uri = explode('/', $request->getPath());
    }

    /**
     * Register HTTP GET Route
     * @param  string $uri
     * @param  callable|array $controller
     * @return static
     */
    public static function get(string $uri, callable|array $controller): static
    {
        $self = new static;

        $self->registerRoute('GET', $uri, $controller);

        return $self;
    }

    /**
     * Register HTTP POST Route
     * @param  string $uri
     * @param  callable|array $controller
     * @return static
     */
    public static function post(string $uri, callable|array $controller): static
    {
        $self = new static;

        $self->registerRoute('POST', $uri, $controller);

        return $self;
    }

    /**
     * Register HTTP PATCH Route
     * @param  string $uri
     * @param  callable|array $controller
     * @return static
     */
    public static function patch(string $uri, callable|array $controller): static
    {
        $self = new static;

        $self->registerRoute('PATCH', $uri, $controller);

        return $self;
    }

    /**
     * Register HTTP DELETE Route
     * @param  string $uri
     * @param  callable|array $controller
     * @return static
     */
    public static function delete(string $uri, callable|array $controller): static
    {
        $self = new static;

        $self->registerRoute('DELETE', $uri, $controller);

        return $self;
    }

    // /**
    //  * Register HTTP GET POST PATCH DELETE on passed controller namespace
    //  * The controller will need method index, show, create, store, edit, update, destroy
    //  * @param  string $uri
    //  * @param  string $controller
    //  * @return static
    //  */
    // public static function resource(string $uri, string $controller): static
    // {
    //     $self = new static;

    //     $self->registerRoute(['index', 'show', 'create', 'store', 'edit', 'store', 'destroy'], $uri, $controller);

    //     return $self;
    // }

    // public function only(array $methods): self
    // {
    //     $this->methods = array_intersect($methods, $this->methods);

    //     return $this;
    // }

    // public function except(array $methods): self
    // {
    //     $this->methods = array_diff($this->methods, $methods);

    //     return $this;
    // }

    /**
     * Register name on Route
     * @param  string $name
     * @return self
     */
    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Register middleware on URI
     * @param  string|array $middleware
     * @return self
     */
    public function middleware(string|array $middleware): self
    {
        $self = new static;

        $this->middleware = $middleware;

        return $self;
    }

    /**
     * Initialize Group, chain this method on after `Group Method`
     * @param  callable $closure
     * @return void
     */
    public function group(callable $closure)
    {
        self::$groupStatus[self::$groupIteration]['middleware'] = self::$groupMiddlewareDefiner;
        self::$groupStatus[self::$groupIteration]['prefix'] = self::$groupPrefixDefiner;
        self::$groupStatus[self::$groupIteration]['name'] = self::$groupNameDefiner;

        self::$groupMiddlewareDefiner = false;
        self::$groupPrefixDefiner = false;
        self::$groupNameDefiner = false;

        self::$groupIteration++;

        call_user_func($closure);

        self::$groupIteration--;

        if (self::$groupStatus[self::$groupIteration]['middleware']) {
            self::$groupMiddlewareIteration--;
            unset(self::$groupMiddleware[self::$groupMiddlewareIteration]);
            if (self::$groupMiddlewareIteration == 0) self::$groupMiddleware = null;
        }

        if (self::$groupStatus[self::$groupIteration]['prefix']) {
            self::$groupPrefixIteration--;
            unset(self::$groupPrefix[self::$groupPrefixIteration]);
            if (self::$groupPrefixIteration == 0) self::$groupPrefix = null;
        }

        if (self::$groupStatus[self::$groupIteration]['name']) {
            self::$groupNameIteration--;
            unset(self::$groupName[self::$groupNameIteration]);
            if (self::$groupNameIteration == 0) self::$groupName = null;
        }

        unset(self::$groupStatus[self::$groupIteration]);
    }

    /**
     * Register middleware for group URI.
     * `Group Method`
     * @param  string|array $middleware
     * @return self
     */
    public static function middlewares(string|array $middleware): self
    {
        $self = new static;

        self::$groupMiddleware[self::$groupMiddlewareIteration] = $middleware;

        self::$groupMiddlewareIteration++;
        self::$groupMiddlewareDefiner = true;

        return $self;
    }

    /**
     * Register prefix for group URI
     * `Group Method`
     * @param  string $uri
     * @return self
     */
    public static function prefix(string $uri): self
    {
        $self = new static;

        self::$groupPrefix[self::$groupPrefixIteration] = $uri;

        self::$groupPrefixIteration++;
        self::$groupPrefixDefiner = true;

        return $self;
    }

    /**
     * Register name for group URI
     * `Group Method`
     * @param  string $name
     * @return self
     */
    public static function names(string $name): self
    {
        $self = new static;

        self::$groupName[self::$groupNameIteration] = $name;

        self::$groupNameIteration++;
        self::$groupNameDefiner = true;

        return $self;
    }

    /**
     * Register the route
     * @param  string|array $method
     * @param  string $uri
     * @param  callable|array $controller
     */
    protected function registerRoute(string|array $method, string $uri, callable|array $controller)
    {
        if (is_array($method))
            $this->methods = $method;
        else
            $this->method = $method;

        $this->uri = explode('/', $uri);
        $this->controller = $controller;
    }
}
