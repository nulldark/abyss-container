<?php

namespace Nulldark\Tests;

use Nulldark\Container\Container;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Container::class)]
class ContainerTest extends TestCase
{

    /**
     * @covers \Nulldark\Container\Container::instance
     * @return void
     */
    public function testSetReturnsTheInstance(): void
    {
        $container = new Container();

        $instance = new \stdClass();
        $resolved = $container->set('acme', $instance);

        $this->assertSame($instance, $resolved);
    }

}