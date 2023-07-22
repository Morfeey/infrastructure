<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Application\Filter\Condition;

use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FieldList\ContractEntityFieldListInterface;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\SortTypeEnum as SortType;

class FilterSortCondition
{
    public function __construct(
        protected readonly ContractEntityFieldListInterface $field,
        protected readonly SortType $sortType
    ) {
    }

    public function getField(): ContractEntityFieldListInterface
    {
        return $this->field;
    }

    public function getSortType(): SortType
    {
        return $this->sortType;
    }
}
