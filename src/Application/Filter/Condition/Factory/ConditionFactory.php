<?php

namespace App\Bundles\InfrastructureBundle\Application\Filter\Condition\Factory;

use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FieldList\ContractEntityFieldListInterface;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterCondition;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\ConditionTypeEnum as Type;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\ConditionWhereType as WhereType;

readonly class ConditionFactory
{
    public function createByArray(array $filterCondition, ContractEntityFieldListInterface $contractEntityFieldList): FilterCondition
    {
        return new FilterCondition(
            $filterCondition['value'],
            $contractEntityFieldList::create($filterCondition['field']),
            Type::tryFrom((int) $filterCondition['type']),
            WhereType::tryFrom((int) $filterCondition['whereType'])
        );
    }
}