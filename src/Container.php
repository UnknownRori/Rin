<?php

namespace UnknownRori\ProjectRin;

use Psr\Container\ContainerInterface;
use UnknownRori\ProjectRin\Exceptions\ContainerKeyNotFound;

/**
 * Built in Dependency Injection Container, 
 * not very useful so try use 3rd party library
 */
class Container implements ContainerInterface
{
    protected $data = [];

    /**
     * Insert new data into container
     * @param  string $id
     * @param  mixed  $data
     * @return void
     */
    public function set(string $id, $data): void
    {
        $this->data[$id] = $data;
    }

    /**
     * Get the data from container using passed id
     * @param mixed $name
     * 
     * @return mixed
     */
    public function get(string $id): mixed
    {
        if ($this->has($id))
            return $this->data[$id];

        throw new ContainerKeyNotFound();
    }

    /**
     * Check if the container has a passed id
     * @param  mixed $id
     * 
     * @return bool
     */
    public function has(string $id): bool
    {
        return array_key_exists($id, $this->data);
    }
}
