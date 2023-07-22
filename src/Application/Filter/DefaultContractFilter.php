<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Application\Filter;

use App\Bundles\InfrastructureBundle\Application\Contract\Filter\DefaultFilterInterface;
use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FieldList\ContractEntityFieldListInterface as FieldList;
use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FilterInterface;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\ConditionTypeEnum as ConditionType;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\ConditionWhereType as ConditionWhereType;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterCondition;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterSortCondition;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\SortTypeEnum as SortType;
use App\Bundles\InfrastructureBundle\Infrastructure\Helper\ArrayCollection\Collection;
use App\Bundles\InfrastructureBundle\Infrastructure\Helper\ArrayCollection\CollectionInterface;

abstract class DefaultContractFilter implements FilterInterface, DefaultFilterInterface
{
    protected CollectionInterface $conditions;
    protected CollectionInterface $sortConditionCollection;
    protected CollectionInterface $linearFilterCollection;
    protected ?int $limit;
    protected ?int $offset;
    protected bool $isDistinct = false;

    public function __construct()
    {
        $this->conditions = new Collection();
        $this->sortConditionCollection = new Collection();
        $this->linearFilterCollection = new Collection();
        $this->limit
            = $this->offset
            = null
        ;
    }

    public function addCondition(FilterCondition $condition): static
    {
        $this->conditions->add($condition);

        return $this;
    }

    public function createCondition(
        mixed $value,
        FieldList $field,
        ConditionType $type = ConditionType::EQUALS,
        ConditionWhereType $whereType = ConditionWhereType::AND
    ): FilterCondition {
        return new FilterCondition($value, $field, $type, $whereType);
    }

    /**
     * @return FilterCondition[]
     */
    public function getConditionCollection(): CollectionInterface
    {
        return $this->conditions;
    }

    public function getSortConditionCollection(): CollectionInterface
    {
        return $this->sortConditionCollection;
    }

    public function clear(): static
    {
        $this->conditions->clear();
        $this->sortConditionCollection->clear();
        $this->linearFilterCollection->clear();
        $this->limit = null;
        $this->offset = null;

        return $this;
    }

    public function isEmpty(): bool
    {
        return $this->conditions->isEmpty();
    }

    public function addSortBy(FieldList $field, SortType $sortType = SortType::ASC): static
    {
        $this->sortConditionCollection->add(
            new FilterSortCondition($field, $sortType)
        );

        return $this;
    }

    public function getLinearFilterCollection(): CollectionInterface
    {
        return $this->linearFilterCollection;
    }

    public function addLinearFilter(FilterInterface $filter): static
    {
        $this->linearFilterCollection->add($filter);

        return $this;
    }

    public function setLimit(int $limit): static
    {
        $this->limit = $limit;

        return $this;
    }

    public function setOffset(int $offset): static
    {
        $this->offset = $offset;

        return $this;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function isDistinct(): bool
    {
        return $this->isDistinct;
    }

    public function setIsDistinct(bool $isDistinct = true): static
    {
        $this->isDistinct = $isDistinct;
        return $this;
    }

    public function __clone(): void
    {
        $this->clear();
    }
}
