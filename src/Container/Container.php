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

namespace Nulldark\Container;

use Closure;
use Nulldark\Container\Internal\Binder;
use Nulldark\Container\Internal\Concrete\Alias;
use Nulldark\Container\Internal\Config;
use Nulldark\Container\Internal\Factory;
use Nulldark\Container\Internal\Storage;
use Nulldark\Container\Internal\State;

/**
 * @package Nulldark\Container
 * @version 0.1.0
 * @license LGPL-2.1
 */
class Container implements ContainerInterface
{
    private State $state;
    private BinderInterface $binder;
    private \Psr\Container\ContainerInterface $container;
    private FactoryInterface $factory;

    public function __construct()
    {
        $storage = new Storage([
           'state' => new State(),
        ]);

        foreach ($storage->config as $property => $class) {
            if (\property_exists($this, $property)) {
                $this->$property = $storage->get($property, $class);
            }
        }

        $shared = new Alias(self::class);

        $this->state->bindings = array_merge($this->state->bindings, [
           \Psr\Container\ContainerInterface::class => $shared,
           BinderInterface::class => $shared,
           FactoryInterface::class => $shared
        ]);
    }


    /**
     * @inheritDoc
     */
    public function bind(string $abstract, mixed $concrete, bool $shared = false): void
    {
        $this->binder->bind($abstract, $concrete);
    }

    /**
     * @inheritDoc
     */
    public function singleton(string $abstract, mixed $concrete): void
    {
        $this->binder->bind($abstract, $concrete, true);
    }

    /**
     * @inheritDoc
     */
    public function get(string $id): mixed
    {
        return $this->container->get($id);
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        return $this->container->has($id);
    }

    /**
     * @inheritDoc
     */
    public function make(string $abstract, array $parameters = []): mixed
    {
        return $this->factory->make($abstract, $parameters);
    }

    /**
     * @inheritDoc
     */
    public function isShared(string $abstract): bool
    {
        return $this->binder->isShared($abstract);
    }
}
