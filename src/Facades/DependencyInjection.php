<?php

namespace UnknownRori\Rin\Facades;

use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionObject;
use ReflectionFunction;
use ReflectionMethod;

/**
 *  A class that wrap around reflection to do automatic dependency injection
 */
class DependencyInjection
{
    /**
     * call the __invoke of class using dependency injection
     * @param  string  $namespace
     * @param  \Psr\Container\ContainerInterface $container
     * @param  array $additionalData
     * @return mixed
     */
    public static function resolveInvoke(string $namespace, ContainerInterface $container, array &$additionalData = []): mixed
    {
        return self::resolveMethodCall($namespace, '__invoke', $container, $additionalData);
    }

    public static function resolveMethodCall(string $namespace, $method, ContainerInterface $container, array &$additionalData = []): mixed
    {
        $class = self::resolveClass($namespace, $container, $additionalData);

        $reflection = new ReflectionObject($class);
        $params = $reflection->getMethod($method)->getParameters();
        $dependency = self::resolveDependency($params, $container, $additionalData);

        return $class->$method(...$dependency);
    }

    /**
     * call the function using dependency injection
     * @param  string  $namespace
     * @param  \Psr\Container\ContainerInterface $container
     * @param  array $additionalData
     * @return mixed
     */
    public static function resolveCall($namespace, ContainerInterface $container, array &$additionalData = []): mixed
    {
        $reflection = new ReflectionFunction($namespace);
        $params = $reflection->getParameters();
        $dependency = self::resolveDependency($params, $container, $additionalData);

        return $reflection->invoke(...$dependency);
    }

    /**
     * Resolve specific class namespace using either container or creating new one
     * @param string $namespace
     * @param \Psr\Container\ContainerInterface $container
     * @param $additionalData
     * @return object
     */
    public static function resolveClass(string $namespace, ContainerInterface $container, array &$additionalData = []): object
    {
        $class = $container->has($namespace) ? $container->get($namespace) : null;

        if (!is_null($class))
            return $class;

        $reflection = new ReflectionClass($namespace);
        $constructor = $reflection->getConstructor();

        $params = $constructor ? $constructor->getParameters : [];
        $dependency = self::resolveDependency($params, $container, $additionalData);

        $additionalData[$namespace] = new $namespace(...$dependency);

        return $additionalData[$namespace];
    }

    /**
     * Resolve the targeted array of ReflectionParameter and return the resolved dependency in array
     * @param  array<\ReflectionParameter> $params
     * @param  \Psr\Container\ContainerInterface $container
     * @param  array $additionalData
     * @return array
     */
    public static function resolveDependency(array $params, ContainerInterface $container, array &$additionalData = [])
    {
        if (!count($params))
            return [];

        $dependency = [];

        foreach ($params as $param) {
            $argument = (string)$param->getType();
            $name = $param->getName();

            if (class_exists($argument)) {
                if (array_key_exists($argument, $additionalData))
                    $dependency[$name] = $additionalData[$argument];
                else
                    $dependency[$name] = $container->get($argument);
            } else {
                if (array_key_exists($name, $additionalData))
                    $dependency[$name] = $additionalData[$name];
                else
                    $dependency[$name] = $container->get($name);
            }
        }

        return $dependency;
    }
}
