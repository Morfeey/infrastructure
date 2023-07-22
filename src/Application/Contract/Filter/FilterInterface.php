<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Application\Contract\Filter;

use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FieldList\ContractEntityFieldListInterface;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\SortTypeEnum;

/** Интерфейс фильтра, для использования в реазации фильтра наружу */
interface FilterInterface
{
    public function clear(): static;
    public function isEmpty(): bool;
    public function addSortBy(ContractEntityFieldListInterface $field, SortTypeEnum $sortType): static;
    public function addLinearFilter(FilterInterface $filter): static;
    public function setLimit(int $limit): static;
    public function setOffset(int $offset): static;
    public function setIsDistinct(bool $isDistinct = true): static;
    public function createFieldList(): ContractEntityFieldListInterface;
}
