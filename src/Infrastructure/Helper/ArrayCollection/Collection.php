<?php


namespace App\Bundles\InfrastructureBundle\Infrastructure\Helper\ArrayCollection;

use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FieldList\ContractEntityFieldListInterface;
use ArrayIterator;
use Closure;
use JetBrains\PhpStorm\Pure;
use ReflectionClass;

class Collection implements CollectionInterface
{
    protected array $items;

    public function __construct(array $listItems = [])
    {
        $this->setItems($listItems);
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function toArray(): array
    {
        return $this->items;
    }

    public function map(Closure $closure): array
    {
        return array_map($closure, $this->items);
    }

    public function first()
    {
        return $this->isEmpty() ? null : reset($this->items);
    }

    public static function createFrom(array $items): static
    {
        return new static($items);
    }

    public function last()
    {
        return $this->isEmpty() ? null : end($this->items);
    }

    public function next()
    {
        return next($this->items);
    }

    public function current()
    {
        return current($this->items);
    }

    public function remove($key): self
    {
        if ($this->__isset($key)) {
            $this->__unset($key);
        }
        return $this;
    }

    public function removeElement($element): bool
    {
        foreach ($this->items as $key => $value) {
            if ($value === $element) {
                $this->__unset($key);

                return true;
            }
        }

        return false;
    }

    public function offsetExists($offset): bool
    {
        return $this->containsKey($offset);
    }

    public function offsetGet($offset): mixed
    {
        return $this->get($offset);
    }

    public function offsetSet($offset, $value): void
    {
        if ($this->__isset($offset)) {
            $this->set($offset, $value);
        }
    }

    public function offsetUnset($offset): void
    {
        $this->remove($this->get($offset));
    }

    public function containsKey($key): bool
    {
        return $this->__isset($key) || array_key_exists($key, $this->items);
    }

    public function contains($element): bool
    {
        return in_array($element, $this->items, true);
    }

    public function exists(Closure $p): bool
    {
        foreach ($this->items as $key => $item) {
            if ($p($key, $item)) {
                return true;
            }
        }

        return false;
    }

    public function get($key)
    {
        return $this->__isset($key) ? $this->items[$key] : null;
    }

    public function getKeys(): array
    {
        return array_keys($this->items);
    }

    public function getValues(): array
    {
        return array_values($this->items);
    }

    public function set($key, $value): self
    {
        $this->items[$key] = $value;

        return $this;
    }

    public function add($element): static
    {
        $this->items[] = $element;

        return $this;
    }

    #[Pure] public function isEmpty(): bool
    {
        return
            $this->items === []
            || $this->count() === 0
            || empty($this->items);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->items);
    }

    public function filter(Closure $p): static
    {
        return static::createFrom(array_filter($this->items, $p, ARRAY_FILTER_USE_BOTH));
    }

    public function conditionIsForAll(Closure $condition): bool
    {
        foreach ($this->items as $key => $item) {
            if (!($condition($key, $item))) {
                return false;
            }
        }

        return true;
    }

    public function __toString()
    {
        return self::class . '@' . spl_object_hash($this);
    }

    public function clear(): self
    {
        $this->items = [];

        return $this;
    }

    public function slice(int $offset, ?int $length = null): array
    {
        return array_slice($this->items, $offset, $length, true);
    }

    public function __isset(string $name): bool
    {
        return isset($this->items[$name]);
    }

    public function __unset(string $name)
    {
        unset($this->items[$name]);
    }

    public function isLast($key): bool
    {
        return array_key_last($this->items) === $key;
    }

    public function isFirst($key): bool
    {
        return array_key_first($this->items) === $key;
    }

    public function merge(CollectionInterface ...$collections): static
    {
        if (!count($collections)) {
            return $this;
        }

        $listCollections = [];
        foreach ($collections as $collection) {
            $listCollections[] = $collection->toArray();
        }

        $this->items = array_merge(...$listCollections);

        return $this;
    }

    public function mergeWithoutReplacement(CollectionInterface ...$collections): static
    {
        if (!count($collections)) {
            return $this;
        }

        foreach ($collections as $collection) {
            foreach ($collection as $item) {
                $this->items[] = $item;
            }
        }

        return $this;
    }

    public function setItems(array $items): static
    {
        $this->items = $items;
        return $this;
    }

    public function sortByCallback(Closure $callback): static
    {
        uasort($this->items, $callback);

        return $this;
    }

    public function resetKeys(): static
    {
        $this->items = array_values($this->items);

        return $this;
    }

    public function findByFieldList(ContractEntityFieldListInterface $field, mixed $value): mixed
    {
        if ($this->isEmpty()) {
            return null;
        }

        $class = new ReflectionClass(get_class($this->first()));
        foreach ($class->getProperties() as $property) {
            if ($property->getName() !== $field->getFieldString()) {
                continue;
            }

            foreach ($this->items as $item) {
                $currentValue = $property->getValue($item);
                if ($currentValue === $value) {
                    return $item;
                }
            }
        }

        return null;
    }

    #[Pure] public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
