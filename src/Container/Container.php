<?php

namespace Nulldark\Container;

class Container implements ContainerInterface
{
    private array $instances;

    public function __construct() {
        $this->instances = [];
    }

    public function set(string $abstract, mixed $instance): mixed
    {
        return $this->instances[$abstract] = $instance;
    }

    public function get(string $id)
    {
        // TODO: Implement get() method.
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->instances);
    }
}