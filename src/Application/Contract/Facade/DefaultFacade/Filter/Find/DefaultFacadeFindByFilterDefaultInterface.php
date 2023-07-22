<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Application\Contract\Facade\DefaultFacade\Filter\Find;

use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FilterInterface as Filter;
use App\Bundles\InfrastructureBundle\Infrastructure\Helper\ArrayCollection\CollectionInterface;

interface DefaultFacadeFindByFilterDefaultInterface
{
    public function findByFilter(Filter $filter): CollectionInterface;
}
