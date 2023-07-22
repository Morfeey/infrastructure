<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Application\Contract\Facade\DefaultFacade;

use App\Bundles\InfrastructureBundle\Application\Contract\Facade\DefaultFacade\Filter\DefaultFacadeDeleteByFilterDefaultInterface;
use App\Bundles\InfrastructureBundle\Application\Contract\Facade\DefaultFacade\Filter\DefaultFacadeGetCountByFilterDefaultInterface;
use App\Bundles\InfrastructureBundle\Application\Contract\Facade\DefaultFacade\Filter\DefaultFindByFilterInterface;

interface DefaultFacadeByFilterInterface extends
    DefaultFindByFilterInterface,
    DefaultFacadeGetCountByFilterDefaultInterface,
    DefaultFacadeDeleteByFilterDefaultInterface
{
}
