<?php

namespace App\Bundles\InfrastructureBundle\Application\Service;

use App\Bundles\InfrastructureBundle\Application\Contract\Facade\ContractFacadeInterface;
use App\Bundles\InfrastructureBundle\Application\Contract\Facade\DefaultFacadeInterface;
use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FilterInterface;
use App\Bundles\InfrastructureBundle\Application\Contract\Service\FilterServiceInterface;
use App\Bundles\InfrastructureBundle\Application\Filter\DefaultContractFilter;
use App\Bundles\InfrastructureBundle\Application\Filter\Factory\FilterFactory;

readonly class FilterService implements FilterServiceInterface
{
    public function __construct(
        private FilterFactory $filterFactory
    ) {
    }

    public function createByArray(array $filterArray, ContractFacadeInterface $facade): FilterInterface
    {
        return $this->filterFactory->createByArray($filterArray, $facade);
    }

    public function toArray(DefaultContractFilter|FilterInterface $filter): array
    {
        return $filter->jsonSerialize();
    }
}