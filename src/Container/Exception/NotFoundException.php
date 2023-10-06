<?php

namespace Nulldark\Container\Exception;

use Psr\Container\NotFoundExceptionInterface;
use RunTimeException;

class NotFoundException extends RunTimeException implements NotFoundExceptionInterface
{

}