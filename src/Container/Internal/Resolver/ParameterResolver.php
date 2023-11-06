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

use Nulldark\Container\Exception\DependencyException;
use Psr\Container\ContainerInterface;

/**
 * @internal
 *
 * @package Nulldark\Container\Internal\Resolver
 * @since 0.1.0
 * @license LGPL-2.1
 */
final class ParameterResolver
{
    /** @var list<mixed> $stack */
    private array $stack = [];

    public function __construct(
        private readonly ContainerInterface $container
    ) {
    }

    /**
     * Resolves a parameters for given method.
     *
     * @param \ReflectionMethod|null $method
     * @param mixed[] $parameters
     *
     * @return list<mixed>
     *
     * @throws DependencyException
     */
    public function resolveParameters(\ReflectionMethod $method = null, array $parameters = []): array
    {
        if ($method === null) {
            return $this->stack;
        }

        foreach ($method->getParameters() as $parameter) {
            /** @var null|\ReflectionNamedType $type */
            $type = $parameter->getType();

            if (\array_key_exists($parameter->getName(), $parameters)) {
                $this->stack[] = $parameters[$parameter->getName()];
            } elseif ($type !== null && !$type->isBuiltin()) {
                $this->stack[] = $this->container->get($parameter->getName());
            } else {
                if ($parameter->isDefaultValueAvailable() || $parameter->isOptional()) {
                    $this->stack[] = $this->getParameterDefaultValue($parameter);
                    continue;
                }

                throw new DependencyException(sprintf(
                    "Parameter `$%s` has no value defined or guessable.",
                    $parameter->getName()
                ));
            }
        }

        return $this->stack;
    }

    private function getParameterDefaultValue(\ReflectionParameter $parameter): mixed
    {
        try {
            return $parameter->getDefaultValue();
        } catch (\ReflectionException) {
            throw new DependencyException(sprintf(
                "The parameter `$%s` has no type defined or guessable. It has a default value," .
                "It has a default value, but the default value can't be read through Reflection",
                $parameter->getName()
            ));
        }
    }
}
