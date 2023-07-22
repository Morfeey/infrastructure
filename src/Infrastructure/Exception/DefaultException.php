<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Exception;

use App\Bundles\InfrastructureBundle\Infrastructure\Exception\ExceptionInterface\DefaultExceptionInterface;
use Exception;

class DefaultException extends Exception implements DefaultExceptionInterface
{

}
