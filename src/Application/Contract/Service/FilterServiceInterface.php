<?php

namespace App\Bundles\InfrastructureBundle\Application\Contract\Service;

use App\Bundles\InfrastructureBundle\Application\Contract\Facade\ContractFacadeInterface;
use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FilterInterface;
use App\Bundles\InfrastructureBundle\Application\Filter\DefaultContractFilter;

interface FilterServiceInterface
{
    public function createByArray(array $filterArray, ContractFacadeInterface $facade): FilterInterface;
    public function toArray(DefaultContractFilter|FilterInterface $filter): array;
}