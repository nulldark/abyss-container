<?php

namespace Nulldark\Tests\Fixtures;

class ClassWithCircularDependencyB
{
    public function __construct(ClassWithCircularDependencyA $self)
    {

    }
}