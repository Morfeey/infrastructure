<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Exception\AccessDenied;

use App\Bundles\InfrastructureBundle\Infrastructure\Exception\DefaultException;
use App\Bundles\InfrastructureBundle\Infrastructure\Exception\ExceptionInterface\AccessDenied\AccessDeniedExceptionInterface;

class AccessDeniedException extends DefaultException implements AccessDeniedExceptionInterface
{

}
