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

use Exception;
use InvalidArgumentException;
use Abyss\Container\Concrete\Alias;
use Abyss\Container\Concrete\Concrete;
use Abyss\Container\Concrete\Factory;
use Abyss\Container\Concrete\Scalar;
use Abyss\Container\Concrete\Shared;
use Abyss\Container\Exception\CircularDependencyException;
use Abyss\Container\Exception\NotFoundException;
use Abyss\Container\Resolver\ConcreteResolver;

use function array_key_exists;

/**
 * @package Abyss\Container
 * @version 0.1.0
 * @license LGPL-2.1
 */
class Container implements ContainerInterface, FactoryInterface, InvokerInterface
{
    protected State $state;

    public function __construct()
    {
        $this->state = new State();

        $shared = new Alias(self::class);

        $this->state->bindings = array_merge($this->state->bindings, [
            ContainerInterface::class => $shared,
            FactoryInterface::class => $shared,
            InvokerInterface::class => $shared
        ]);
    }

    /**
     * @inheritDoc
     */
    public function get(string $id): mixed
    {
        try {
            return $this->make($id, []);
        } catch (Exception $e) {
            if ($this->has($id) || $e instanceof CircularDependencyException) {
                throw $e;
            }

            throw new NotFoundException($id, (int)$e->getCode(), $e);
        }
    }

    /**
     * @inheritDoc
     */
    public function make(string $abstract, array $parameters = []): mixed
    {
        if (isset($this->state->buildStack[$abstract])) {
            throw new CircularDependencyException(
                "Circular dependency detected while trying to resolve entry '{$abstract}'"
            );
        }

        $this->state->buildStack[$abstract] = true;

        if (array_key_exists($abstract, $this->state->instances)) {
            return $this->state->instances[$abstract];
        }

        $concrete = $this->state->bindings[$abstract] ?? null;

        if ($concrete === null) {
            return $this->build($abstract, $parameters);
        }

        try {
            return match ($concrete::class) {
                Alias::class => $this->resolveAlias($concrete, $abstract, $parameters),
                Factory::class => $this->call($concrete->factory, $parameters),
                Shared::class => $concrete->value,
                Scalar::class => $concrete->value,
                default => $concrete
            };
        } finally {
            \array_pop($this->state->buildStack);
        }
    }

    /**
     * Instantiate a concrete instance.
     *
     * @template T
     *
     * @param class-string<T>|string $abstract
     * @param mixed[] $parameters Parameters to construct new class.
     *
     * @return ($abstract is class-string ? T : mixed)
     *
     * @throws NotFoundException
     */
    public function build(string $abstract, array $parameters = []): mixed
    {
        if (!(class_exists($abstract) || interface_exists($abstract))) {
            throw new NotFoundException(sprintf(
                "Can't resolve: undefined class`%s`.",
                $abstract
            ));
        }

        try {
            return (new ConcreteResolver($this))->resolve($abstract, $parameters);
        } finally {
            array_pop($this->state->buildStack);
        }
    }

    /**
     * @param Alias $concrete
     * @param string $abstract
     * @param mixed[] $parameters
     *
     * @return mixed
     *
     * @throws NotFoundException
     */
    private function resolveAlias(Alias $concrete, string $abstract, array $parameters): mixed
    {
        $instance = $concrete->value === $abstract
            ? $this->build($abstract, $parameters)
            : $this->make($concrete->value, $parameters);

        if ($concrete->shared) {
            $this->state->instances[$abstract] = $instance;
        }

        return $instance;
    }

    /**
     * @inheritDoc
     */
    public function has(string $id): bool
    {
        return array_key_exists($id, $this->state->bindings) || array_key_exists($id, $this->state->instances);
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
    public function bind(string $abstract, mixed $concrete = null, bool $shared = false): void
    {
        $object = match (true) {
            $concrete instanceof Concrete => $concrete,
            $concrete instanceof \Closure => new Factory($concrete, $shared),
            is_string($concrete) => new Alias($concrete, $shared),
            is_scalar($concrete) => new Scalar($concrete),
            is_object($concrete) => new Shared($concrete),
            default => throw new InvalidArgumentException("Unknown `concrete` type.")
        };

        $this->state->bindings[$abstract] = $object;
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
    public function isShared(string $abstract): bool
    {
        return array_key_exists($abstract, $this->state->instances);
    }

    /**
     * @inheritDoc
     */
    public function call(callable|string|array $callback, array $parameters = []): mixed
    {
        return $this->getInvoker()->call($callback, $parameters);
    }

    /**
     * Gets a new Invoker instance.
     *
     * @return InvokerInterface
     */
    public function getInvoker(): InvokerInterface
    {
        return new Invoker($this);
    }
}
