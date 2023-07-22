<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Entity\FieldList;

use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FieldList\ContractEntityFieldListInterface;

abstract class DefaultFieldList implements ContractEntityFieldListInterface
{
    private string $fieldString = '';
    private mixed $value = null;

    private function setFieldString(string $fieldString): static
    {
        $this->fieldString = $fieldString;

        return $this;
    }

    public function getFieldString(): string
    {
        return $this->fieldString;
    }

    public function setValue(mixed $value): static
    {
        $this->value = $value;

        return $this;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public static function create(string $fieldString): static
    {
        return (new static())->setFieldString($fieldString);
    }
}
