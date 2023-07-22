<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Application\Contract\Facade\DefaultFacade\Filter;

use App\Bundles\InfrastructureBundle\Application\Contract\Facade\DefaultFacade\Filter\Find\DefaultFacadeFindFieldByFilterDefaultInterface as FindFieldByFilterInterface;
use App\Bundles\InfrastructureBundle\Application\Contract\Facade\DefaultFacade\Filter\Find\DefaultFacadeFindFieldsByFilterDefaultInterface as FindFieldsByFilterInterface;
use App\Bundles\InfrastructureBundle\Application\Contract\Facade\DefaultFacade\Filter\Find\DefaultFacadeFindByFilterDefaultInterface as FindByFilterInterface;
use App\Bundles\InfrastructureBundle\Application\Contract\Facade\DefaultFacade\Filter\Find\DefaultFacadeFindIdListByFilterDefaultInterface as FindIdListByFilterInterface;
use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FilterInterface as Filter;

interface DefaultFindByFilterInterface extends
    FindByFilterInterface,
    FindFieldsByFilterInterface,
    FindFieldByFilterInterface,
    FindIdListByFilterInterface
{
}
