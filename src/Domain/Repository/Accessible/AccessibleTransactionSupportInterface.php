<?php

namespace App\Bundles\InfrastructureBundle\Domain\Repository\Accessible;

use App\Bundles\InfrastructureBundle\Domain\Repository\IsolationLevel\TransactionIsolationLevelInterface;
use Closure;

interface AccessibleTransactionSupportInterface
{
    public function isActiveTransaction(): bool;
    public function transactionStart(?TransactionIsolationLevelInterface $level = null): static;
    public function commit(): static;
    public function rollback(): static;
    public function transactional(Closure $closure, ?TransactionIsolationLevelInterface $isolationLevel = null): static;
}
