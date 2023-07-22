<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Application\Contract\Facade\DefaultFacade\Filter\Find;

use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FilterInterface as Filter;

interface DefaultFacadeFindIdListByFilterDefaultInterface
{
    public function findIdListByFilter(Filter $filter): array;
}
