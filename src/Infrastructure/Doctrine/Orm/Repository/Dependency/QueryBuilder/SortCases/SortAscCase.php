<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\SortCases;

use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\SortTypeEnum;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterSortCondition as SortCondition;

class SortAscCase extends DefaultSortCase implements SortCaseInterface
{
    protected function createStringType(SortCondition $sortCondition): string
    {
        return strtoupper($sortCondition->getSortType()->name);
    }

    public function isCanBeProcessed(SortCondition $sortCondition): bool
    {
        return $sortCondition->getSortType() === SortTypeEnum::ASC;
    }
}
