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

namespace Nulldark\Tests\Resolver;

use Nulldark\Container\Container;
use Nulldark\Container\Exception\DependencyException;
use Nulldark\Container\Resolver\ParameterResolver;
use Nulldark\Container\Resolver\ParameterResolverInterface;
use Nulldark\Tests\Fixtures\TypedMethod;
use Nulldark\Tests\Fixtures\TypedMethodWithDefaultParameter;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

final class ParameterResolverTest extends TestCase
{
    private ParameterResolverInterface $resolver;

    public function setUp(): void
    {
        $this->resolver = new ParameterResolver(
            new Container()
        );
    }

    /**
     * @covers \Nulldark\Container\Resolver\ParameterResolver::resolveParameters
     * @return void
     */
    public function testReturnsEmptyArrayIfMethodIsNull(): void
    {
        $this->assertEmpty(
            $this->resolver->resolveParameters()
        );
    }

    /**
     * @covers \Nulldark\Container\Resolver\ParameterResolver::resolveParameters
     * @return void
     */
    public function testReturnParameterFromGivenParametersIfIsBuiltin(): void
    {
        $foo = new TypedMethod();

        $arguments = $this->resolver->resolveParameters(
            new ReflectionMethod($foo, 'foo'),
            ['a' => 200]
        );

        $this->assertCount(1, $arguments);
        $this->assertSame($arguments[0], 200);
    }

    /**
     * @covers \Nulldark\Container\Resolver\ParameterResolver::resolveParameters
     * @return void
     */
    public function testReturnDefaultParameterValueIfIsNotGiven(): void
    {
        $foo = new TypedMethodWithDefaultParameter();

        $arguments = $this->resolver->resolveParameters(
            new ReflectionMethod($foo, 'foo')
        );

        $this->assertCount(1, $arguments);
        $this->assertSame($arguments[0], 10);
    }

    /**
     * @covers \Nulldark\Container\Resolver\ParameterResolver::resolveParameters
     * @return void
     */
    public function testThrowExceptionIfIsNotGuessable(): void
    {
        $this->expectException(DependencyException::class);
        $this->expectExceptionMessage('Parameter $a has no value defined or guessable');

        $foo = new TypedMethod();

        $this->resolver->resolveParameters(
            new ReflectionMethod($foo, 'foo')
        );
    }
}
