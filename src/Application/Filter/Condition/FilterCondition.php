<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Application\Filter\Condition;

use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FieldList\ContractEntityFieldListInterface;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\ConditionTypeEnum as Type;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\ConditionWhereType as WhereType;
use JsonSerializable;

readonly class FilterCondition implements JsonSerializable
{
    public function __construct(
        private mixed $value,
        private ContractEntityFieldListInterface $field,
        private Type $type,
        private WhereType $whereType
    ) {
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function getWhereType(): WhereType
    {
        return $this->whereType;
    }

    public function getField(): ContractEntityFieldListInterface
    {
        return $this->field;
    }

    public function jsonSerialize(): array
    {
        return [
            'value' => $this->value,
            'field' => $this->field->getFieldString(),
            'type' => $this->type->toInt(),
            'whereType' => $this->whereType->toInt(),
        ];
    }
}
