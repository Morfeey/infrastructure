<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Application\Contract\Filter\FieldList;

interface ContractEntityFieldListInterface
{
    public function getFieldString(): string;
    public function setValue(mixed $value): static;
    public function getValue(): mixed;
}
