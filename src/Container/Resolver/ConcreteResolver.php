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

namespace Nulldark\Container\Resolver;

use Nulldark\Container\ContainerInterface;
use Nulldark\Container\Exception\ResolveException;
use ReflectionClass;
use ReflectionException;

/**
 * @author Dominik Szamburski
 * @package Container
 * @subpackage Resolver
 * @license LGPL-2.1
 * @version 0.1.0
 */
final class ConcreteResolver implements ResolverInterface
{
    private readonly ParameterResolverInterface $parameterResolver;

    public function __construct(ContainerInterface $container)
    {
        $this->parameterResolver = new ParameterResolver($container);
    }

    /**
     * @inheritDoc
     */
    public function resolve(string $concrete, array $parameters): object
    {
        try {
            $reflector = new ReflectionClass($concrete);
        } catch (ReflectionException) {
            throw new ResolveException(
                "Entry '$concrete' cannot be resolved: the class is not instantiable"
            );
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
