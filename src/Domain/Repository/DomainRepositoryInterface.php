<?php

namespace App\Bundles\InfrastructureBundle\Domain\Repository;

use App\Bundles\InfrastructureBundle\Domain\Repository\Accessible\AccessibleByFilterInterface;
use App\Bundles\InfrastructureBundle\Domain\Repository\Accessible\AccessibleTransactionSupportInterface;
use App\Bundles\InfrastructureBundle\Domain\Repository\Accessible\DefaultAccessibleInterface;

interface DomainRepositoryInterface extends
    DefaultAccessibleInterface,
    AccessibleByFilterInterface,
    AccessibleTransactionSupportInterface
{
}
