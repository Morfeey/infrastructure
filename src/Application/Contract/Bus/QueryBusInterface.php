<?php

namespace App\Bundles\InfrastructureBundle\Application\Contract\Bus;

use App\Bundles\InfrastructureBundle\Application\Contract\Query\QueryInterface;

interface QueryBusInterface
{
    public function execute(QueryInterface $query): mixed;
}
