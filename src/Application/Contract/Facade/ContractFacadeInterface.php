<?php

namespace App\Bundles\InfrastructureBundle\Application\Contract\Facade;

use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FieldList\ContractEntityFieldListInterface;
use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FilterInterface;

interface ContractFacadeInterface
{
    public function createFilter(): FilterInterface;
    public function createFieldList(): ContractEntityFieldListInterface;
}
