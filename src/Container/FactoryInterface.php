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

use Abyss\Container\Exception\CircularDependencyException;
use Abyss\Container\Exception\NotFoundException;

/**
 * @package Abyss\Container
 * @since 0.2.0
 * @license LGPL-2.1
 */
interface FactoryInterface
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

    /**
     * Create instance of requested class using binding class aliases and set of parameters provided.
     *
     * @template T of object
     *
     * @param string|class-string<T> $abstract
     * @param mixed[] $parameters
     *
     * @return ($abstract is class-string ? T : mixed)
     *
     * @throws \Abyss\Container\Exception\CircularDependencyException
     * @throws \Abyss\Container\Exception\NotFoundException
     */
    public function make(string $abstract, array $parameters = []): mixed;
}
