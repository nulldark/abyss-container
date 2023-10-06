<?php

namespace Nulldark\Tests\Fixtures;

class ClassWithCircularDependencyA
{
    public function __construct(ClassWithCircularDependencyB $self)
    {

    }
}