<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Exception\NotFound;

use App\Bundles\InfrastructureBundle\Infrastructure\Exception\DefaultException;
use App\Bundles\InfrastructureBundle\Infrastructure\Exception\ExceptionInterface\NotFound\NotFoundExceptionInterface;
use Throwable;

class NotFoundException extends DefaultException implements NotFoundExceptionInterface
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if (!$message) {
            $message = 'Page not found';
        }

        parent::__construct($message, $code, $previous);
    }
}
