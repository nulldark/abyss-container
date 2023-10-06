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

namespace Nulldark\Tests\Resolver;

use Nulldark\Container\Container;
use Nulldark\Container\Exception\ResolveException;
use Nulldark\Container\Resolver\ConcreteResolver;
use Nulldark\Container\Resolver\ResolverInterface;
use Nulldark\Tests\Fixtures\SampleClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use stdClass;

#[CoversClass(ConcreteResolver::class)]
class ConcreteResolverTest extends TestCase
{
    private ResolverInterface $resolver;

    public function setUp(): void
    {
        $this->resolver = new ConcreteResolver(
            new Container()
        );
    }

    public function testWithConstructor(): void
    {
        $object = $this->resolver->resolve(SampleClass::class, ['foo' => 10]);

        $this->assertIsObject($object);
        $this->assertEquals(SampleClass::class, $object::class);
        $this->assertSame(10, $object->foo);
    }

    /**
     * @covers \Nulldark\Container\Resolver\ConcreteResolver::resolve
     * @return void
     */
    public function testWithoutConstructorReturnsInstance(): void
    {
        $object = $this->resolver->resolve(stdClass::class, []);

        $this->assertIsObject($object);
        $this->assertEquals(new stdClass(), $object);
    }

    /**
     * @covers \Nulldark\Container\Resolver\ConcreteResolver::resolve
     * @return void
     */
    public function testNotInstantiableClassThrowsException(): void
    {
        $this->expectException(ResolveException::class);
        $this->expectExceptionMessage("Entry 'foobar::class' cannot be resolved: the class is not instantiable");

        $this->resolver->resolve('foobar::class', []);
    }
}
