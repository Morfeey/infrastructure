<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\ConditionCases\Dto;

readonly class ConditionParameterDto
{
    public function __construct(
        private mixed  $value,
        private string $key
    ) {
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getKey(): string
    {
        return $this->key;
    }
}
