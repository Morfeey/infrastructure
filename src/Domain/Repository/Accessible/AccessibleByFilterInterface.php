<?php

namespace App\Bundles\InfrastructureBundle\Domain\Repository\Accessible;

use App\Bundles\InfrastructureBundle\Application\Contract\Filter\DefaultFilterInterface;
use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FieldList\ContractEntityFieldListInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Entity\CustomEntityInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Helper\ArrayCollection\CollectionInterface;

interface AccessibleByFilterInterface
{
    public function findByFilter(DefaultFilterInterface $filter): CollectionInterface;
    public function findOneByFilter(DefaultFilterInterface $filter): ?CustomEntityInterface;
    public function requireOneByFilter(DefaultFilterInterface $filter): CustomEntityInterface;
    public function findByFilterFieldList(DefaultFilterInterface $filter, ContractEntityFieldListInterface ...$fieldList): array;
    public function findByFilterField(DefaultFilterInterface $filter, ContractEntityFieldListInterface $field): array;
    public function findByFilterSingleField(DefaultFilterInterface $filter, ContractEntityFieldListInterface $field): mixed;
    public function findIdListByFilter(DefaultFilterInterface $filter): array;
    public function updateByFilter(DefaultFilterInterface $filter, ContractEntityFieldListInterface ...$fieldList): static;
    public function deleteByFilter(DefaultFilterInterface $filter): static;

    public function getCountByFilter(DefaultFilterInterface $filter): int;

    public function min(?DefaultFilterInterface $filter = null, ?ContractEntityFieldListInterface $field = null): int;
    public function max(?DefaultFilterInterface $filter = null, ?ContractEntityFieldListInterface $field = null): int;
}
