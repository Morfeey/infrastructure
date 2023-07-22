<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Exception\NoContent;

use App\Bundles\InfrastructureBundle\Infrastructure\Exception\DefaultException;
use App\Bundles\InfrastructureBundle\Infrastructure\Exception\ExceptionInterface\NoContent\NoContentExceptionInterface;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

class NoContentException extends DefaultException implements NoContentExceptionInterface
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        if ($message === '') {
            $message = 'Content is empty';
        }

        parent::__construct($message, $code, $previous);
    }
}
