<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Application\Contract\Filter;

use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FieldList\ContractEntityFieldListInterface;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\SortTypeEnum;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterCondition;
use App\Bundles\InfrastructureBundle\Application\Filter\DefaultContractFilter;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\ConditionTypeEnum as ConditionType;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\ConditionWhereType as WhereType;
use App\Bundles\InfrastructureBundle\Domain\Repository\DomainRepositoryInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Helper\ArrayCollection\CollectionInterface;

/**
 * Интерфейс фильтра для использования в реализации репозитория
 * Не нуждается в имплементировании при использовании @link DefaultContractFilter как родителя
 */
interface DefaultFilterInterface extends \JsonSerializable
{
    public function createCondition(
        mixed $value,
        ContractEntityFieldListInterface $field,
        ConditionType $type = ConditionType::EQUALS,
        WhereType $whereType = WhereType::AND
    ): FilterCondition;

    public function addSortBy(ContractEntityFieldListInterface $field, SortTypeEnum $sortType): static;
    public function addCondition(FilterCondition $condition): static;
    public function getConditionCollection(): CollectionInterface;
    public function getSortConditionCollection(): CollectionInterface;
    public function getLinearFilterCollection(): CollectionInterface;
    public function createRepository(): DomainRepositoryInterface;
    public function getLimit(): ?int;
    public function getOffset(): ?int;
    public function isEmpty(): bool;
    public function isDistinct(): bool;
}
