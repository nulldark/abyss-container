<?php

namespace Nulldark\Container\Resolver;

interface ResolverInterface
{
    /**
     * Resolve the given type from the container.
     *
     * @param string $concrete
     * @param array<string, object|string|int> $parameters
     * @return object
     *
     * @throws ResolveException
     * @throws DependencyException
     */
    public function resolve(string $concrete, array $parameters): object;
}