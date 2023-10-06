<?php

namespace Nulldark\Tests\Fixtures;

class TypedMethod
{
    public function foo(int $a): int
    {
        return $a;
    }
}