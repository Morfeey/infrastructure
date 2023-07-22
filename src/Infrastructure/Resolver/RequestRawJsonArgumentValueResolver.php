<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Resolver;

use App\Bundles\InfrastructureBundle\Infrastructure\Resolver\SimpleResolve\ValueService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

readonly class RequestRawJsonArgumentValueResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private ValueService $valueService
    ) {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $values = json_decode($request->getContent(), true);
        $value = $values[$argument->getName()] ?? null;

        return
            json_last_error() === JSON_ERROR_NONE
            && in_array($argument->getType(), RequestSimpleArgumentsValueResolver::SIMPLE_TYPES)
            && ($value || $argument->isNullable() || $argument->hasDefaultValue())
        ;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        return $this->valueService->resolve(json_decode($request->getContent(), true), $argument);
    }
}
