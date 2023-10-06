<?php

namespace Nulldark\Container\Resolver;

use Nulldark\Container\Exception\DependencyException;
use Nulldark\Container\Exception\ResolveException;

/**
 * @author Dominik Szamburski
 * @package Container
 * @subpackage Resolver
 * @license LGPL-2.1
 * @version 0.1.0
 */
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