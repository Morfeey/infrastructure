<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Exception\BadAuthorization;

use App\Bundles\InfrastructureBundle\Infrastructure\Exception\DefaultException;
use App\Bundles\InfrastructureBundle\Infrastructure\Exception\ExceptionInterface\BadAuthorization\BadAuthorizationInterface;

class BadAuthorizationException extends DefaultException implements BadAuthorizationInterface
{

}
