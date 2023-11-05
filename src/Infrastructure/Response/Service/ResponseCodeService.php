<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Response\Service;

use App\Bundles\InfrastructureBundle\Infrastructure\Exception\ExceptionInterface\AccessDenied\AccessDeniedExceptionInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Exception\ExceptionInterface\BadAuthorization\BadAuthorizationInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Exception\ExceptionInterface\BadRequest\BadRequestExceptionInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Exception\ExceptionInterface\DefaultExceptionInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Exception\ExceptionInterface\ExternalSystem\ExternalSystemExceptionInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Exception\ExceptionInterface\NoContent\NoContentExceptionInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Exception\ExceptionInterface\NotFound\NotFoundExceptionInterface;
use Throwable;

class ResponseCodeService
{
    private const CODE_BY_INTERFACE = [
        NoContentExceptionInterface::class => 204,
        BadRequestExceptionInterface::class => 400,
        BadAuthorizationInterface::class => 401,
        AccessDeniedExceptionInterface::class => 403,
        NotFoundExceptionInterface::class => 404,
        ExternalSystemExceptionInterface::class => 527,
        DefaultExceptionInterface::class => 500,
    ];

    public function getByThrowable(Throwable $throwable): int
    {
        if ($throwable->getCode() > 0 && $throwable->getCode() <= 599) {
            return $throwable->getCode();
        }

        foreach (self::CODE_BY_INTERFACE as $interfaceClass => $code) {
            if ($throwable instanceof $interfaceClass) {
                return $code;
            }
        }

        return self::CODE_BY_INTERFACE[DefaultExceptionInterface::class];
    }
}
