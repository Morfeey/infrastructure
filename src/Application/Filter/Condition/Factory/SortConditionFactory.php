<?php

namespace App\Bundles\InfrastructureBundle\Application\Filter\Condition\Factory;

use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FieldList\ContractEntityFieldListInterface;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterSortCondition;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\SortTypeEnum as SortType;

readonly class SortConditionFactory
{
    public function createByArray(array $filterSortCondition, ContractEntityFieldListInterface $contractEntityFieldList): FilterSortCondition
    {
        return new FilterSortCondition(
            $contractEntityFieldList::create($filterSortCondition['field']),
            SortType::tryFrom((int) $filterSortCondition['sortType'])
        );
    }
}