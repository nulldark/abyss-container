<?php

namespace Nulldark\Container;

use Closure;

interface ContainerInterface extends \Psr\Container\ContainerInterface
{
    /**
     * Register an exists instance as singleton in the container.
     *
     * @param string $abstract
     * @param mixed $instance
     * @return mixed
     */
    public function set(string $abstract, mixed $instance): mixed;

    /**
     * Resolve given abstract.
     *
     * @param string $abstract
     * @param array $parameters
     *
     * @return mixed
     */
    public function make(string $abstract, array $parameters = []): mixed;

    /**
     * Instantiate a concrete instance.
     *
     * @param string|Closure $concrete
     * @param array $parameters
     * @return mixed
     */
    public function build(string|Closure $concrete, array $parameters = []): mixed;

    /**
     * Register new binding in the container.
     *
     * @param string $abstract
     * @param Closure|string|null $concrete
     * @param bool $shared
     * @return void
     */
    public function bind(string $abstract, Closure|string|null $concrete = null, bool $shared = false): void;
}