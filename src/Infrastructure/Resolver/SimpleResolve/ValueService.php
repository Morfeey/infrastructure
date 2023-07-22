<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Resolver\SimpleResolve;

use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class ValueService
{
    public function resolve(array $values, ArgumentMetadata $argument): iterable
    {
        $value = $values[$argument->getName()] ?? null;
        if (!$value && $argument->hasDefaultValue()) {
            yield $argument->getDefaultValue();
        }

        if (!$value && $argument->isNullable()) {
            yield null;
        }

        switch ($argument->getType()) {
            case 'string':
                yield trim($value);
                break;
            case 'int':
                yield (int) $value;
                break;
            case 'bool':
                yield (bool) $value;
                break;
            case 'float':
                yield (float) $value;
                break;
            case 'array':
                yield (array) $value;
                break;
            default:
            yield $value;
        }
    }
}
