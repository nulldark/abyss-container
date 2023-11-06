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

namespace Nulldark\Container\Internal;

use Nulldark\Container\FactoryInterface;
use Psr\Container\ContainerInterface;

/**
 * @internal
 *
 * @package Nulldark\Container\Internal
 * @since 0.2.0
 * @license LGPL-2.1
 */
final class Container implements ContainerInterface
{
    private State $state;
    private FactoryInterface $factory;

    public function __construct(Storage $storage)
    {
        $storage->set('container', $this);

        $this->factory = $storage->get('factory', FactoryInterface::class);
        $this->state = $storage->get('state', State::class);
    }

    /**
     *
     *
     * @template T
     * @param string|class-string<T> $id
     *
     * @return ($id is class-string ? T : mixed)
     */
    public function get(string $id): mixed
    {
        return $this->factory->make($id, []);
    }

    public function has(string $id): bool
    {
        return \array_key_exists($id, $this->state->bindings) || \array_key_exists($id, $this->state->instances);
    }
}
