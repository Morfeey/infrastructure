<?php

namespace App\Bundles\InfrastructureBundle\Application\Filter\Factory;

use App\Bundles\InfrastructureBundle\Application\Contract\Facade\ContractFacadeInterface;
use App\Bundles\InfrastructureBundle\Application\Contract\Filter\DefaultFilterInterface;
use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FilterInterface;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Factory\ConditionFactory;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Factory\SortConditionFactory;

readonly class FilterFactory
{
    public function __construct(
        private ConditionFactory $conditionFactory,
        private SortConditionFactory $sortConditionFactory
    ) {
    }

    public function createByArray(array $filterArray, ContractFacadeInterface $facade): FilterInterface
    {
        /** @var $filter DefaultFilterInterface */
        $filter = $facade->createFilter();
        foreach ($filterArray['conditions'] ?? [] as $condition) {
            $filter->addCondition($this->conditionFactory->createByArray($condition, $facade->createFieldList()));
        }

        foreach ($filterArray['sortConditionCollection'] ?? [] as $sortConditionCollection) {
            $sortCondition = $this->sortConditionFactory->createByArray($sortConditionCollection, $facade->createFieldList());
            $filter->addSortBy($sortCondition->getField(), $sortCondition->getSortType());
        }

        foreach ($filterArray['linearFilterCollection'] ?? [] as $linearFilterConllection) {
            $linearFilter = $this->createByArray($linearFilterConllection, $facade);
        }

        /** @var $filter FilterInterface */
        $limit = isset($filterArray['limit']) && $filterArray['limit'] !== null ? (int) $filterArray['limit']: null;
        $offset = isset($filterArray['offset']) && $filterArray['offset'] !== null ? (int) $filterArray['offset'] : null;
        $isDistinct = isset($filterArray['isDistinct']) && $filterArray['isDistinct'] !== null ? (bool) $filterArray['isDistinct'] : false;

        if (null !== $limit) {
            $filter->setLimit($limit);
        }

        if (null !== $offset) {
            $filter->setOffset($offset);
        }

        if ($isDistinct) {
            $filter->setIsDistinct($isDistinct);
        }

        return $filter;
    }
}