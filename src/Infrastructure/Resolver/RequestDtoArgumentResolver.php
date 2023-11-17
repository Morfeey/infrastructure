<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Resolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;

class RequestDtoArgumentResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private SerializerInterface $serializer
    ) {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getName() === 'requestDto';
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        yield $this->serializer->deserialize($request->getContent(), $argument->getType(), 'json');
    }
}