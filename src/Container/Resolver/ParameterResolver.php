<?php

/**
 * Copyright (C) 2023 Dominik Szamburski
 *
 * This file is part of EntityManager
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

use Nulldark\Container\Exception\DependencyException;
use Psr\Container\ContainerInterface;
use ReflectionMethod;

/**
 * @author Dominik Szamburski
 * @package Container
 * @subpackage Resolver
 * @license LGPL-2.1
 * @version 0.1.0
 */
final class ParameterResolver implements ParameterResolverInterface
{
    private array $stack = [];

    public function __construct(
        private readonly ContainerInterface $container
    )
    {
    }


    /**
     * @inheritDoc
     */
    public function resolveParameters(ReflectionMethod $method = null, array $parameters = []): array
    {
        if ($method === null) {
            return [];
        }

        foreach ($method->getParameters() as $parameter) {
            if ($parameter->getType() !== null && !$parameter->getType()->isBuiltin()) {
                $this->stack[] = array_key_exists($parameter->getType()->getName(), $parameters)
                    ? $parameters[$parameter->getType()->getName()]
                    : $this->container->get($parameter->getType()->getName());

                continue;
            }

            if (!array_key_exists($parameter->getName(), $parameters)) {
                if (!($parameter->isDefaultValueAvailable() || $parameter->isOptional())) {
                    throw new DependencyException(
                        sprintf(
                            "Parameter $%s has no value defined or guessable",
                            $parameter->getName()
                        )
                    );
                }

                $this->stack[] = $parameter->getDefaultValue();
                continue;
            }

            $this->stack[] = $parameters[$parameter->getName()];
        }

        return $this->stack;
    }

}