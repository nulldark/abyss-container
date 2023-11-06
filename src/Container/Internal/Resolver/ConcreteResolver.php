<?php

/**
 * Copyright (C) 2023 Dominik Szamburski
 *
 * This file is part of nulldark\container
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 */

namespace Nulldark\Container\Internal\Resolver;

use Nulldark\Container\Exception\NotFoundException;
use Nulldark\Container\Exception\ResolveException;
use Nulldark\Container\Internal\Context;
use Psr\Container\ContainerInterface;

/**
 * @internal
 *
 * @package Nulldark\Container\Internal\Resolver
 * @since 0.1.0
 * @license LGPL-2.1
 */
final class ConcreteResolver
{
    private readonly ParameterResolver $parameterResolver;

    public function __construct(
        private readonly ContainerInterface $container
    ) {
        $this->parameterResolver = new ParameterResolver($this->container);
    }

    /**
     *
     * @template T
     *
     * @param class-string<T>|string $abstract
     * @param list<mixed> $parameters
     *
     * @return ($abstract is class-string ? T : mixed)
     *
     * @throws ResolveException
     * @throws NotFoundException
     */
    public function resolve(string $abstract, array $parameters = []): mixed
    {
        try {
            if (!\class_exists($abstract)) {
                throw new NotFoundException();
            }

            $reflector = new \ReflectionClass($abstract);
        } catch (\ReflectionException) {
            throw new ResolveException(sprintf(
                "Entry `%s` cannot be resolved: the class is not instantiable.",
                $abstract
            ));
        }

        $constructor = $reflector->getConstructor();

        if ($constructor === null) {
            return $reflector->newInstanceWithoutConstructor();
        }


        $args = $this->parameterResolver->resolveParameters(
            $constructor,
            $parameters
        );

        return $reflector->newInstanceArgs($args);
    }
}
