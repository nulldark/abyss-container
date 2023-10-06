<?php

namespace Nulldark\Container\Resolver;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;
use ReflectionMethod;

interface ParameterResolverInterface
{
    /**
     * Resolve parameters for given method.
     *
     * @param ReflectionMethod|null $method
     * @param array $parameters
     * @return array
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function resolveParameters(ReflectionMethod $method = null, array $parameters = []): array;
}