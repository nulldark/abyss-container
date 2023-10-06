<?php

namespace Nulldark\Tests\Fixtures;

class TypedMethodWithDefaultParameter
{
    public function foo(int $a = 10): int
    {
        return $a;
    }
}