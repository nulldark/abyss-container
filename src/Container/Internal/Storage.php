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

/**
 * @internal
 *
 * @package Nulldark\Container\Internal
 * @license LGPL-2.1
 * @since 0.2.0
 */
final class Storage
{
    /**
     * @param array<string, object> $objects
     */
    public function __construct(
        private array $objects = [],
        public Registry $config = new Registry(),
    ) {
    }

    /**
     * @template T of object
     *
     * @param string $id
     * @param class-string<T> $class
     *
     * @return T
     */
    public function get(string $id, string $class): object
    {
        $className = $this->config->$id;
        $result = $this->objects[$id] ?? new $className($this);

        \assert($result instanceof $class);

        return $result;
    }

    /**
     * @param string $id
     * @param object $instance
     *
     * @return void
     */
    public function set(string $id, object $instance): void
    {
        $this->objects[$id] = $instance;
    }
}
