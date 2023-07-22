<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Helper\ArrayCollection;

use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FieldList\ContractEntityFieldListInterface;
use ArrayAccess;
use Closure;
use Countable;
use IteratorAggregate;
use JsonSerializable;

/**
 * An ArrayCollection is a Collection implementation that wraps a regular PHP array.
 *
 * Warning: Using (un-)serialize() on a collection is not a supported use-case
 * and may break when we change the internals in the future. If you need to
 * serialize a collection use {@link toArray()} and reconstruct the collection
 * manually.
 *
 * @psalm-template TKey of array-key
 * @psalm-template T
 * @template-implements Collection<TKey,T>
 * @psalm-consistent-constructor
 */
interface CollectionInterface extends Countable, IteratorAggregate, ArrayAccess, JsonSerializable
{
    public function add($element): static;
    public function setItems(array $items): static;
    public function count(): int;
    public function toArray(): array;
    public function map(Closure $closure): array;
    public function first();
    public function last();
    public function isLast($key): bool;
    public function isFirst($key): bool;
    public function merge(self ...$collection): static;
    public function mergeWithoutReplacement(CollectionInterface ...$collections): static;
    public function exists(Closure $p): bool;
    public static function createFrom(array $items): static;
    public function filter(Closure $p): static;
    public function isEmpty(): bool;
    public function sortByCallback(Closure $callback): static;
    public function resetKeys(): static;
    public function findByFieldList(ContractEntityFieldListInterface $field, mixed $value): mixed;
}
