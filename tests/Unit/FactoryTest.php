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

namespace Nulldark\Tests\Unit;

use Nulldark\Container\Container;
use Nulldark\Container\Internal\Factory;
use Nulldark\Tests\Unit\Fixture\SampleClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Factory::class)]
#[CoversClass(Container::class)]
class FactoryTest extends TestCase
{
    public function testMakeWithGivenArguments(): void
    {
        $container = new Container();

        $instance1 = $container->make(SampleClass::class, ['foo' => 10]);
        $instance2 = $container->make(SampleClass::class, ['foo' => 10]);

        $this->assertEquals(SampleClass::class, $instance1::class);
        $this->assertSame($instance1->foo, $instance2->foo);
    }
}
