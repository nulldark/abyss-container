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

use Nulldark\Container\Exception\NotFoundException;
use Psr\Container\ContainerInterface as BaseContainerInterface;

/**
 * @package Nulldark\Container
 * @version 0.1.0
 * @license LGPL-2.1
 */
interface ContainerInterface extends
    BinderInterface,
    FactoryInterface,
    BaseContainerInterface
{
    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @template T
     * @param string|class-string<T> $id Entry name or a class name.
     *
     * @return ($id is class-string ? T : mixed)
     *
     * @throws NotFoundException
     */
    public function get(string $id): mixed;
}
