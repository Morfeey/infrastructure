<?php

namespace App\Bundles\InfrastructureBundle\Domain\Repository\Accessible;

use App\Bundles\InfrastructureBundle\Domain\Entity\DomainEntityInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Helper\ArrayCollection\CollectionInterface;

interface DefaultAccessibleInterface
{
    public function create(): DomainEntityInterface;
    public function clear(): static;
    public function truncate(): static;
    public function getCount(): int;

    public function findByIdentifier(mixed $identification): ?DomainEntityInterface;
    public function findAll(): CollectionInterface;
    public function findAllIterable(): iterable;

    public function save(DomainEntityInterface $entity): static;
    public function saveCollection(CollectionInterface $entityCollection): static;
    public function saveList(array $listItems, bool $inTransaction = false): static;

    public function delete(DomainEntityInterface $entity): static;
    public function deleteCollection(CollectionInterface $entityCollection): static;
    public function deleteByIdList(array $idList): static;

    public function update(DomainEntityInterface $entity): static;
}
