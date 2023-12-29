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

namespace Abyss\Container\Concrete;

use function gettype;
use function var_export;

/**
 * @package Abyss\Container\Concrete
 * @since 0.2.0
 * @license LGPL-2.1
 */
final class Scalar extends Concrete
{
    public function __construct(
        public int|float|string|bool $value
    ) {
    }

    public function __toString(): string
    {
        return sprintf("Value (%s) %s", gettype($this->value), var_export($this->value, true));
    }
}
