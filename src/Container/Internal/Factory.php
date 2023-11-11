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

use Nulldark\Container\Concrete\Alias;
use Nulldark\Container\Concrete\Scalar;
use Nulldark\Container\Concrete\Shared;
use Nulldark\Container\Exception\CircularDependencyException;
use Nulldark\Container\Exception\NotFoundException;
use Nulldark\Container\FactoryInterface;
use Nulldark\Container\Internal\Resolver\ConcreteResolver;

use function array_key_exists;
use function class_exists;
use function interface_exists;
use function sprintf;

/**
 * @internal
 *
 * @package Nulldark\Container\Internal
 * @since 0.2.0
 * @license LGPL-2.1
 */
final class Factory implements FactoryInterface
{
    private State $state;
    private Container $container;

    /** @var array<string, bool> $buildStack */
    private array $buildStack = [];

    public function __construct(Storage $storage)
    {
        $storage->set('factory', $this);

        $this->container = $storage->get('container', Container::class);
        $this->state = $storage->get('state', State::class);
    }

    /**
     * @inheritDoc
     */
    public function make(string $abstract, array $parameters = []): mixed
    {
        if (array_key_exists($abstract, $this->state->instances)) {
            return $this->state->instances[$abstract];
        }

        $concrete = $this->state->bindings[$abstract] ?? null;

        if ($concrete === null) {
            return $this->build($abstract, $parameters);
        }

        return match ($concrete::class) {
            Alias::class => $this->resolveAlias($concrete, $abstract, $parameters),
            Shared::class => $concrete->value,
            Scalar::class => $concrete->value,
            default => $concrete
        };
    }

    /**
     * Instantiate a concrete instance.
     *
     * @template T
     *
     * @param class-string<T>|string $abstract
     * @param list<mixed> $parameters Parameters to construct new class.
     *
     * @return ($abstract is class-string ? T : mixed)
     */
    public function build(string $abstract, array $parameters = []): mixed
    {
        if (!(class_exists($abstract) || interface_exists($abstract))) {
            throw new NotFoundException(sprintf(
                "Can't resolve: undefined class`%s`.",
                $abstract
            ));
        }

        if (isset($this->buildStack[$abstract])) {
            throw new CircularDependencyException(
                "Circular dependency detected while trying to resolve entry '{$abstract}'"
            );
        }

        $this->buildStack[$abstract] = true;

        try {
            return (new ConcreteResolver($this->container))->resolve($abstract, $parameters);
        } finally {
            array_pop($this->buildStack);
        }
    }

    /**
     * @param Alias $concrete
     * @param string $abstract
     * @param list<mixed> $parameters
     *
     * @return mixed
     */
    private function resolveAlias(Alias $concrete, string $abstract, array $parameters): mixed
    {
        $instance = $concrete->value === $abstract
            ? $this->build($abstract, $parameters)
            : $this->make($concrete->value, $parameters);

        if ($concrete->singleton) {
            $this->state->instances[$abstract] = $instance;
        }

        return $instance;
    }
}
