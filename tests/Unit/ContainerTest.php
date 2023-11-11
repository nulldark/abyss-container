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
use Nulldark\Container\Exception\CircularDependencyException;
use Nulldark\Tests\Unit\Fixture\ClassACircularDependency;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Container::class)]
class ContainerTest extends TestCase
{
    public function testMakeIfCircularDependencyThrowsException(): void
    {
        self::expectException(CircularDependencyException::class);
        self::expectExceptionMessage(
            "Circular dependency detected while trying to resolve entry " .
            "'Nulldark\Tests\Unit\Fixture\ClassACircularDependency'"
        );

        $container = new Container();
        $container->make(ClassACircularDependency::class);
    }
}
