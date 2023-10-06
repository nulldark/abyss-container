<?php

namespace Nulldark\Container\Resolver;

use Nulldark\Container\ContainerInterface;
use Nulldark\Container\Exception\ResolveException;
use ReflectionException;

final class ConcreteResolver implements ResolverInterface
{
    private readonly ParameterResolverInterface $parameterResolver;

    public function __construct(ContainerInterface $container) {
        $this->parameterResolver = new ParameterResolver($container);
    }

    /**
     * @inheritDoc
     */
    public function resolve(string $concrete, array $parameters): object
    {
        try {
            $reflector = new \ReflectionClass($concrete);
        } catch (ReflectionException) {
            throw new ResolveException(
                "Entry '$concrete' cannot be resolved: the class is not instantiable"
            );
        }

        $constructor = $reflector->getConstructor();

        if ($constructor === null) {
            return $reflector->newInstanceWithoutConstructor();
        }

        $args = $this->parameterResolver->resolveParameters(
            $constructor,
            $parameters
        );

        return $reflector->newInstanceArgs($args);
    }
}