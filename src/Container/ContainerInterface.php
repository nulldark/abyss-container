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

use InvalidArgumentException;
use Abyss\Container\Exception\NotFoundException;
use Psr\Container\ContainerInterface as BaseContainerInterface;

/**
 * @package Abyss\Container
 * @version 0.1.0
 * @license LGPL-2.1
 *
 * @phpstan-type TConcrete = class-string|non-empty-string|object|callable
 */
interface ContainerInterface extends BaseContainerInterface
{
    /**
     * Returns an entry of the container by its name.
     *
     * @template T of object
     *
     * @param string|class-string<T> $id
     *  $id Entry name or a class name
     *
     * @return ($id is class-string ? T : mixed)
     *  The retrieved service.
     *
     * @throws \Abyss\Container\Exception\CircularDependencyException
     * @throws \Abyss\Container\Exception\NotFoundException
     */
    public function get(string $id): mixed;

    /**
     * Registers a binding in the container.
     *
     * @param string $abstract
     * @param TConcrete $concrete
     * @param bool $shared
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function bind(string $abstract, string|object|callable $concrete, bool $shared = false): void;

    /**
     * Registers a shared binding in the container.
     *
     * @param string $abstract
     * @param TConcrete $concrete
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function singleton(string $abstract, string|object|callable $concrete): void;

    /**
     * Registers a scalar binding in the container.
     *
     * @param string $abstract
     * @param int|float|string|bool $scalar
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function scalar(string $abstract, int|float|string|bool $scalar): void;

    /**
     * Registers a alias binding in the container.
     *
     * @param string $abstract
     * @param string $alias
     *
     * @return void
     *
     * @throws InvalidArgumentException
     */
    public function alias(string $abstract, string $alias): void;

    /**
     * Check if given abstract is shared.
     *
     * @param string $abstract
     * @return bool
     */
    public function isShared(string $abstract): bool;
}
