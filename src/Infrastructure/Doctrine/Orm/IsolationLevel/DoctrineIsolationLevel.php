<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\IsolationLevel;

use App\Bundles\InfrastructureBundle\Domain\Repository\IsolationLevel\TransactionIsolationLevelInterface;
use Doctrine\DBAL\TransactionIsolationLevel;

class DoctrineIsolationLevel implements TransactionIsolationLevelInterface
{
    private function __construct(protected int $value)
    {
    }

    public static function readUnCommitted(): static
    {
        return new static(TransactionIsolationLevel::READ_UNCOMMITTED);
    }

    public static function readCommitted(): static
    {
        return new static(TransactionIsolationLevel::READ_COMMITTED);
    }

    public static function repeatableRead(): static
    {
        return new static(TransactionIsolationLevel::REPEATABLE_READ);
    }

    public static function serialize(): static
    {
        return new static(TransactionIsolationLevel::SERIALIZABLE);
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
