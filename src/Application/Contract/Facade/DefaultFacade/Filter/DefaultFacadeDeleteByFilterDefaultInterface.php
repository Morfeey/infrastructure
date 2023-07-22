<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Application\Contract\Facade\DefaultFacade\Filter;

use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FilterInterface as Filter;

interface DefaultFacadeDeleteByFilterDefaultInterface
{
    public function deleteByFilter(Filter $filter): static;
}
