<?php

namespace UnknownRori\Rin\Http;

use Exception;
use Psr\Container\ContainerInterface;
use UnknownRori\Rin\Application;
use UnknownRori\Rin\Facades\DependencyInjection;
use UnknownRori\Rin\Exceptions\RouteNotFound;

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
            $this->registerMiddleware();
            $this->registerName();

            $this->commit();
        }
    }

    public static function serve(ContainerInterface $container, Request $request, MiddlewareHandler $middlewareHandler): void
    {
        $uri = $request->getPath();
        if ($uri[-1] == '/' && strlen($uri) > 1)
            $uri = substr_replace($uri, '', -1);

        $uri = explode('/', $uri);
        $uriRoute = self::$route[$request->method()];
        $additionalData = [
            Request::class => $request
        ];

        $max = count($uri);

        for ($i = 0; $i < $max; $i++) {
            if (array_key_exists($uri[$i], $uriRoute))
                $uriRoute = $uriRoute[$uri[$i]];
            else {
                if ($uri[$i] == '')
                    throw new RouteNotFound();

                foreach ($uriRoute as $key => $value) {
                    $matches = [];
                    preg_match_all("/\{(\w+)\}/", $key, $matches);

                    if (array_key_exists(1, $matches)) {
                        $additionalData[$matches[1][0]] = $uri[$i];
                        $uriRoute = $uriRoute[$key];

                        if (array_key_exists($i + 1, $uri))
                            if (array_key_exists($uri[$i + 1], $uriRoute))
                                break;
                    }
                }
            }
        }

        if (!array_key_exists('__controller', $uriRoute))
            throw new RouteNotFound();

        $controller = $uriRoute['__controller'];
        $middleware = $uriRoute['__middleware'];

        $middlewareHandler->run($middleware);

        if (is_callable($controller))
            DependencyInjection::resolveCall($controller, $container, $additionalData);

        $middlewareHandler->run($middleware);
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
     * For debugging purposes (only work if `APP_DEBUG` is on)
     * Dumping out all route, named route, and group iteration.
     * @return array|void
     */
    public static function dump(): ?array
    {
        if (Application::$config->debug) return [
            'Route' => self::$route,
            'Named Route' => self::$nameRoute,
            'Group Nest' => self::$groupIteration,
            'Group Middleware Interation' => self::$groupMiddlewareIteration,
            'Group Name Interation' => self::$groupNameIteration,
            'Group Prefix Interation' => self::$groupPrefixIteration,
            'Group Name' => self::$groupName,
            'Group Prefix' => self::$groupPrefix,
            'Group Middleware' => self::$groupMiddleware,
            'Group Name Definer' => self::$groupNameDefiner,
            'Group Prefix Definer' => self::$groupPrefixDefiner,
            'Group Middleware Definer' => self::$groupMiddlewareDefiner,
            'Group Status' => self::$groupStatus,
        ];
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

        $this->uri = $uri;
        $this->controller = $controller;
    }

    protected function registerPrefix()
    {
        // Todo : Merge prefix with group
    }

    protected function registerName()
    {
        self::$nameRoute[$this->name] = $this->uri;
        // Todo : Merge Name from group
    }

    protected function registerMiddleware()
    {
        // Todo : Merge the middleware with group
        // if (isset(self::$groupMiddleware)) {
        //     $mergedMiddleware = [];

        //     for ($i = 0; $i < count(self::$groupMiddleware); $i++) {
        //         if (isset(self::$groupMiddleware[$i])) {
        //             if (is_array(self::$groupMiddleware[$i])) {
        //                 $mergedMiddleware = array_merge($mergedMiddleware, self::$groupMiddleware[$i]);
        //             } else {
        //                 $mergedMiddleware = array_merge($mergedMiddleware, [self::$groupMiddleware[$i]]);
        //             }
        //         }
        //     }

        //     $this->middleware = $mergedMiddleware;
        // }
    }

    /**
     * Commit the current route definition into global definition
     */
    protected function commit()
    {
        $uri = explode('/', $this->uri);
        $uriRoute = &self::$route[$this->method];
        $max = count($uri);

        for ($i = 0; $i < $max; $i++) {
            if (!array_key_exists($uri[$i], $uriRoute))
                $uriRoute[$uri[$i]] = [];

            $uriRoute = &$uriRoute[$uri[$i]];

            if ($i == $max - 1) {
                $uriRoute['__middleware'] = is_null($this->middleware) ? [] : $this->middleware;
                $uriRoute['__controller'] = $this->controller;
            }
        }
    }
}
