<?php

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