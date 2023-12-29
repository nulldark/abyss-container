<?php

/**
 * Copyright (C) 2023 Dominik Szamburski
 *
 * This file is part of abyss\container
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

namespace Abyss\Container;

use Abyss\Container\Exception\ContainerException;
use Abyss\Container\Resolver\ParameterResolver;

/**
 * @package Abyss\Container
 * @since 0.4.0
 * @license LGPL-2.1
 */
final class Invoker implements InvokerInterface
{
    private ParameterResolver $resolver;

    public function __construct(
        private readonly ContainerInterface $container
    ) {
        $this->resolver = new ParameterResolver($this->container);
    }


    /**
     * @inheritDoc
     */
    public function call(callable|string|array $callback, array $parameters = []): mixed
    {
        if (\is_array($callback)) {
            // if method is not set, set default one.
            if (!isset($callback[1])) {
                $callback[1] = '__invoke';
            }

            [$instance, $method] = $callback;

            if (\is_string($instance)) {
                $instance = $this->container->get($instance);
            }

            try {
                $method = new \ReflectionMethod($instance, $method);

                return $method->invokeArgs(
                    $instance,
                    $this->resolver->resolveParameters($method, $parameters)
                );
            } catch (\ReflectionException $e) {
                throw new ContainerException($e->getMessage(), $e->getCode(), $e);
            }
        }

        if (\is_string($callback) && \is_callable($callback)) {
            $callback = $callback(...);
        }

        if ($callback instanceof \Closure) {
            try {
                $reflection = new \ReflectionFunction($callback);

                return $reflection->invokeArgs(
                    $this->resolver->resolveParameters($reflection, $parameters)
                );
            } catch (\ReflectionException $e) {
                throw new ContainerException($e->getMessage(), $e->getCode(), $e);
            }
        }

        throw new \RuntimeException("Can't resolve given callable.");
    }
}
