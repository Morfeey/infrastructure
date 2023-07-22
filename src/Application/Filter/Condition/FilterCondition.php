<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Application\Filter\Condition;

use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FieldList\ContractEntityFieldListInterface;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\ConditionTypeEnum as Type;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\ConditionWhereType as WhereType;

class FilterCondition
{
    public function __construct(
        protected readonly mixed $value,
        protected readonly ContractEntityFieldListInterface $field,
        protected readonly Type $type,
        protected readonly WhereType $whereType
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
}
