<?php

namespace Nulldark\Tests\Fixtures;


class ContainerDependent
{
    public function __construct(
        public ContainerContract $impl
    ) {}
}