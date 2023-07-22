<?php

namespace App\Bundles\InfrastructureBundle\Domain\Repository\IsolationLevel;

interface TransactionIsolationLevelInterface
{
    public static function readCommitted(): static;
    public static function readUnCommitted(): static;
    public static function repeatableRead(): static;
    public static function serialize(): static;

    public function getValue(): mixed;
}
