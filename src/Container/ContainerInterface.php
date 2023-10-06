<?php

namespace Nulldark\Container;

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
}