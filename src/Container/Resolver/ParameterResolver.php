<?php

namespace Nulldark\Container\Resolver;

use Nulldark\Container\Exception\DependencyException;
use Psr\Container\ContainerInterface;
use ReflectionMethod;

/**
 * @author Dominik Szamburski
 * @package Container
 * @subpackage Resolver
 * @license LGPL-2.1
 * @version 0.1.0
 */
final class ParameterResolver implements ParameterResolverInterface
{
    private array $stack = [];

    public function __construct(
        private readonly ContainerInterface $container
    )
    {
    }


    /**
     * @inheritDoc
     */
    public function resolveParameters(ReflectionMethod $method = null, array $parameters = []): array
    {
        if ($method === null) {
            return [];
        }

        foreach ($method->getParameters() as $parameter) {
            if ($parameter->getType() !== null && !$parameter->getType()->isBuiltin()) {
                $this->stack[] = array_key_exists($parameter->getType()->getName(), $parameters)
                    ? $parameters[$parameter->getType()->getName()]
                    : $this->container->get($parameter->getType()->getName());

                continue;
            }

            if (!array_key_exists($parameter->getName(), $parameters)) {
                if (!($parameter->isDefaultValueAvailable() || $parameter->isOptional())) {
                    throw new DependencyException(
                        sprintf(
                            "Parameter $%s has no value defined or guessable",
                            $parameter->getName()
                        )
                    );
                }

                $this->stack[] = $parameter->getDefaultValue();
                continue;
            }

            $this->stack[] = $parameters[$parameter->getName()];
        }

        return $this->stack;
    }

}