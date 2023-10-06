<?php

namespace Nulldark\Tests;

use Nulldark\Container\Container;
use Nulldark\Container\Exception\CircularDependencyException;
use Nulldark\Tests\Fixtures\ClassWithCircularDependencyA;
use Nulldark\Tests\Fixtures\DependedClass;
use Nulldark\Tests\Fixtures\SampleClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface;

#[CoversClass(Container::class)]
class ContainerTest extends TestCase
{

    /**
     * @covers \Nulldark\Container\Container::set
     * @return void
     */
    public function testSetReturnsTheInstance(): void
    {
        $container = new Container();

        $instance = new \stdClass();
        $resolved = $container->set('acme', $instance);

        $this->assertSame($instance, $resolved);
    }

    /**
     * @covers \Nulldark\Container\Container::get
     * @return void
     */
    public function testEntryNotFoundThrowsException(): void
    {
        $this->expectException(NotFoundExceptionInterface::class);

        $container = new Container();
        $container->get('test');
    }

    /**
     * @covers \Nulldark\Container\Container::make
     * @return void
     */
    public function testResolveInstanceWithGivenArguments(): void
    {
        $container = new Container();

        $instance1 = $container->make(SampleClass::class, ['foo' => 10]);
        $instance2 = $container->make(SampleClass::class, ['foo' => 10]);

        $this->assertEquals(SampleClass::class, $instance1::class);
        $this->assertSame($instance1->foo, $instance2->foo);
    }

    /**
     * @covers \Nulldark\Container\Container::make
     * @return void
     */
    public function testResolveInstanceWithArgumentsFromContainer(): void
    {
        $container = new Container();

        $instance1 = $container->set(SampleClass::class, new SampleClass(10));
        $instance2 = $container->make(DependedClass::class);

        $this->assertEquals(DependedClass::class, $instance2::class);
        $this->assertSame($instance1, $instance2->sampleClass);
    }

    /**
     * @covers \Nulldark\Container\Container::make
     * @return void
     */
    public function testResolveIfCircularDependencyThrowsException(): void
    {
        $this->expectException(CircularDependencyException::class);
        $this->expectExceptionMessage("Circular dependency detected while trying to resolve entry 'Nulldark\Tests\Fixtures\ClassWithCircularDependencyA'");

        $container = new Container();
        $container->make(ClassWithCircularDependencyA::class);
    }

    /**
     * @covers \Nulldark\Container\Container::set
     * @return void
     */
    public function testContainerCanSetAnyValue()
    {
        $container = new Container();
        $container->set('foo', 'string');
        $container->set('bar', 10);

        $this->assertEquals('string', $container->get('foo'));
        $this->assertEquals(10, $container->get('bar'));
    }
}