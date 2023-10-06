<?php

namespace Nulldark\Tests\Fixtures;

class DependedClass
{
    public function __construct(
        public SampleClass $sampleClass
    ) {}
}