<?php

namespace Nulldark\Container;

use Closure;
use Exception;
use Nulldark\Container\Exception\CircularDependencyException;
use Nulldark\Container\Exception\NotFoundException;
use Nulldark\Container\Resolver\ConcreteResolver;

class Container implements ContainerInterface
{
    private array $bindings = [];
    private array $buildStack = [];
    private array $instances = [];

    public function __construct()
    {

    }

    /**
     * @inheritDoc
     */
    public function get(string $id): mixed
    {
        try {
            return $this->make($id);
        } catch (Exception $e) {
            if ($this->has($id) || $e instanceof CircularDependencyException) {
                throw $e;
            }

            throw new NotFoundException("Entry '$id' not found", $e->getCode(), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        return array_key_exists($id, $this->instances);
    }

    /**
     * @inheritDoc
     */
    public function set(string $abstract, mixed $instance): mixed
    {
        return $this->instances[$abstract] = $instance;
    }

    /**
     * @inheritDoc
     */
    public function build(string|Closure $concrete, array $parameters = []): mixed
    {
        if ($concrete instanceof Closure) {
            return $concrete($this);
        }

        if (isset($this->buildStack[$concrete])) {
            throw new CircularDependencyException(
                "Circular dependency detected while trying to resolve entry '{$concrete}'"
            );
        }

        $this->buildStack[$concrete] = true;

        try {
            $object = (new ConcreteResolver($this))->resolve($concrete, $parameters);
        } finally {
            array_pop($this->buildStack);
        }

        return $object;
    }

    /**
     * @inheritDoc
     */
    public function make(string $abstract, array $parameters = []): mixed
    {
        if (isset($this->instances[$abstract])) {
            return $this->instances[$abstract];
        }

        $concrete = $this->getConcrete($abstract);

        return ($concrete === $abstract || $concrete instanceof Closure)
            ? $this->build($concrete, $parameters)
            : $this->make($abstract);
    }

    /**
     * @param string|callable $abstract
     * @return mixed
     */
    private function getConcrete(string|callable $abstract): mixed
    {
        return is_string($abstract) && isset($this->bindings[$abstract])
            ? $this->bindings[$abstract]['concrete']
            : $abstract;
    }
}