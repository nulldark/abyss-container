<?php

namespace Nulldark\Tests\Resolver;

use Nulldark\Container\Container;
use Nulldark\Container\Exception\ResolveException;
use Nulldark\Container\Resolver\ConcreteResolver;
use Nulldark\Container\Resolver\ResolverInterface;
use Nulldark\Tests\Fixtures\SampleClass;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

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
        $object = $this->resolver->resolve(\stdClass::class, []);

        $this->assertIsObject($object);
        $this->assertEquals(new \stdClass(), $object);
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