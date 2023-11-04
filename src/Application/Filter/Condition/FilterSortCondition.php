<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Application\Filter\Condition;

use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FieldList\ContractEntityFieldListInterface;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\SortTypeEnum as SortType;
use JsonSerializable;

readonly class FilterSortCondition implements JsonSerializable
{
    public function __construct(
        private ContractEntityFieldListInterface $field,
        private SortType $sortType
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

    public function jsonSerialize(): array
    {
        return [
            'field' => $this->field->getFieldString(),
            'sortType' => $this->sortType->toInt()
        ];
    }
}
