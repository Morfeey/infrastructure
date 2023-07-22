<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Exception\ExternalSystem;

use App\Bundles\InfrastructureBundle\Infrastructure\Exception\DefaultException;
use App\Bundles\InfrastructureBundle\Infrastructure\Exception\ExceptionInterface\ExternalSystem\ExternalSystemExceptionInterface;

class ExternalSystemException extends DefaultException implements ExternalSystemExceptionInterface
{

}
