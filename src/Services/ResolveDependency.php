<?php

namespace UnknownRori\Rin\Services;

use Psr\Container\ContainerInterface;
use ReflectionFunction;
use ReflectionClass;
use ReflectionObject;

/**
 * A services to resolve dependency injection
 */
class ResolveDependency
{

    /**
     * call the __invoke of class using dependency injection
     * @param  string  $namespace
     * @param  \Psr\Container\ContainerInterface $container
     * @param  array $additionalData
     * @return mixed
     */
    public static function resolveInvoke(string $namespace, ContainerInterface $container, array $additionalData = []): mixed
    {
        $reflection = new ReflectionClass($namespace);
        $constructor = $reflection->getConstructor();
        $params = $constructor ? $constructor->getParameters : [];
        $dependency = self::resolveDependency($params, $container, $additionalData);
        $class = new $namespace(...$dependency);

        $reflection = new ReflectionObject($class);
        $params = $reflection->getMethod('__invoke')->getParameters();
        $dependency = self::resolveDependency($params, $container, $additionalData);

        return $class(...$dependency);
    }

    /**
     * call the function using dependency injection
     * @param  string  $namespace
     * @param  \Psr\Container\ContainerInterface $container
     * @param  array $additionalData
     * @return mixed
     */
    public static function resolveCall(string $namespace, ContainerInterface $container, array $additionalData = []): mixed
    {
        $reflection = new ReflectionFunction($namespace);
        $params = $reflection->getParameters();
        $dependency = self::resolveDependency($params, $container, $additionalData);

        return $reflection->invoke(...$dependency);

    }

    public static function resolveDependency(array $params, ContainerInterface $container, array $additionalData)
    {
        if (!count($params))
            return [];

        $dependency = [];

        foreach ($params as $param) {
            $argument = (string)$param->getType();

            if (class_exists($argument)) {
                if (array_key_exists($argument, $additionalData))
                    $dependency[$argument] = $additionalData[$argument];
                else
                    $dependency[$argument] = $container->get($argument);
            }
            else {
                $name = $param->getName();
                if (array_key_exists($name, $additionalData))
                    $dependency[$name] = $additionalData[$name];
                else
                    $dependency[$name] = $container->get($name);
            }
        }

        return $dependency;
    }
}