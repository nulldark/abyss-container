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

namespace Nulldark\Container\Concrete;

/**
 * @package Nulldark\Container\Concrete
 * @since 0.4.0
 * @license LGPL-2.1
 */
final class Factory extends Concrete
{

    public readonly \Closure $factory;
    public readonly bool $shared;

    public function __construct(callable $callable, bool $shared = false)
    {
        $this->factory = $callable(...);
        $this->shared = $shared;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return "Factory from function();";
    }
}