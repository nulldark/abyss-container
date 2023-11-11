<?php

    require __DIR__ . '/vendor/autoload.php';

    class SampleClass {
        public function __construct(public int $id = 10) {}
    }

    $container = new \Nulldark\Container\Container();
    $container->bind(SampleClass::class, new \Nulldark\Container\Concrete\Shared(new SampleClass(10)));

    $t = $container->make(SampleClass::class);
    var_dump($t);