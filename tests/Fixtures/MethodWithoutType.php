<?php

namespace Nulldark\Tests\Fixtures;

class MethodWithoutType
{
    public function foo($a): int
    {
        return $a;
    }
}