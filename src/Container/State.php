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

use Nulldark\Container\Concrete\Concrete;

/**
 * @package Nulldark\Container\Internal
 * @since 0.2.0
 * @license LGPL-2.1
 */
final class State
{
    /**
     * The container's bindings.
     *
     * @var array<string, Concrete> $bindings
     */
    public array $bindings = [];

    /**
     * The container's shared instances.
     *
     * @var array<string, mixed> $instances
     */
    public array $instances = [];

    /**
     * The stack of concretions currently being built.
     *
     * @var array<string, bool> $buildStack
     */
    public array $buildStack = [];
}
