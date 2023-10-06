<?php

/**
 * Copyright (C) 2023 Dominik Szamburski
 *
 * This file is part of EntityManager
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

/**
 * @author Dominik Szamburski
 * @package Container
 * @license LGPL-2.1
 * @version 0.1.0
 */
interface ContainerInterface extends \Psr\Container\ContainerInterface
{
    /**
     * Register an exists instance as singleton in the container.
     *
     * @param string $abstract
     * @param mixed $instance
     * @return mixed
     */
    public function set(string $abstract, mixed $instance): mixed;

    /**
     * Register a shared binding in container.
     *
     * @param string $abstract
     * @param Closure|string|null $concrete
     * @return void
     */
    public function singleton(string $abstract, Closure|string|null $concrete = null): void;

    /**
     * Resolve given abstract.
     *
     * @param string $abstract
     * @param array $parameters
     *
     * @return mixed
     */
    public function make(string $abstract, array $parameters = []): mixed;

    /**
     * Instantiate a concrete instance.
     *
     * @param string|Closure $concrete
     * @param array $parameters
     * @return mixed
     */
    public function build(string|Closure $concrete, array $parameters = []): mixed;

    /**
     * Register new binding in the container.
     *
     * @param string $abstract
     * @param Closure|string|null $concrete
     * @param bool $shared
     * @return void
     */
    public function bind(string $abstract, Closure|string|null $concrete = null, bool $shared = false): void;

    /**
     * Check if given abstract is shared.
     *
     * @param string $abstract
     * @return bool
     */
    public function isShared(string $abstract): bool;
}
