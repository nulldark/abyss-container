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

use InvalidArgumentException;
use Nulldark\Container\BinderInterface;
use Nulldark\Container\Concrete\Alias;
use Nulldark\Container\Concrete\Concrete;
use Nulldark\Container\Concrete\Scalar;
use Nulldark\Container\Concrete\Shared;

use function array_key_exists;
use function is_object;
use function is_scalar;
use function is_string;

/**
 * @internal
 *
 * @package Nulldark\Container\Internal
 * @since 0.2.0
 * @license LGPL-2.1
 */
final class Binder implements BinderInterface
{
    private readonly State $state;

    public function __construct(Storage $storage)
    {
        $storage->set('binder', $this);

        $this->state = $storage->get('state', State::class);
    }

    /**
     * @inheritDoc
     */
    public function singleton(string $abstract, mixed $concrete = null): void
    {
        $this->bind($abstract, $concrete, true);
    }

    /**
     * @inheritDoc
     */
    public function scalar(string $abstract, int|float|string|bool $scalar): void
    {
        $this->bind($abstract, new Scalar($scalar));
    }

    /**
     * @inheritDoc
     */
    public function alias(string $abstract, string $alias): void
    {
        $this->bind($abstract, new Alias($alias));
    }

    /**
     * @inheritDoc
     */
    public function bind(string $abstract, mixed $concrete = null, bool $shared = false): void
    {
        $object = match (true) {
            $concrete instanceof Concrete => $concrete,
            is_string($concrete) => new Alias($concrete, $shared),
            is_scalar($concrete) => new Scalar($concrete),
            is_object($concrete) => new Shared($concrete),
            default => throw new InvalidArgumentException("Unknown `concrete` type")
        };

        $this->state->bindings[$abstract] = $object;
    }

    /**
     * @inheritDoc
     */
    public function isShared(string $abstract): bool
    {
        return array_key_exists($abstract, $this->state->instances);
    }
}
