<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Application\Contract\Facade\DefaultFacade\Filter\Find;

use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FieldList\ContractEntityFieldListInterface as ContractField;
use App\Bundles\InfrastructureBundle\Application\Contract\Filter\FilterInterface as Filter;

interface DefaultFacadeFindFieldsByFilterDefaultInterface
{
    public function findByFilterFieldList(Filter $filter, ContractField ...$field): array;
}
