<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Resolver;

use App\Bundles\InfrastructureBundle\Infrastructure\Resolver\SimpleResolve\ValueService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

readonly class RequestSimpleArgumentsValueResolver implements ArgumentValueResolverInterface
{
    public const SIMPLE_TYPES = ['string', 'int', 'float', 'bool', 'array'];

    public function __construct(
        private ValueService $valueService
    ) {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return
            in_array($argument->getType(), self::SIMPLE_TYPES, true)
            && ($request->get($argument->getName()) || $argument->hasDefaultValue() || $argument->isNullable())
        ;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        return $this->valueService->resolve($request->request->all(), $argument);
    }
}
