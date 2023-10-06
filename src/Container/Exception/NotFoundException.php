<?php

namespace Nulldark\Container\Exception;

use Psr\Container\NotFoundExceptionInterface;
use RunTimeException;

/**
 * @author Dominik Szamburski
 * @package Container
 * @subpackage Exception
 * @license LGPL-2.1
 * @version 0.1.0
 */
class NotFoundException extends RunTimeException implements NotFoundExceptionInterface
{

}