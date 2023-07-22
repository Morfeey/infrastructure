<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Exception\BadRequest;

use App\Bundles\InfrastructureBundle\Infrastructure\Exception\DefaultException;
use App\Bundles\InfrastructureBundle\Infrastructure\Exception\ExceptionInterface\BadRequest\BadRequestExceptionInterface;

class BadRequestException extends DefaultException implements BadRequestExceptionInterface
{

}
