<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\Context;

use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterSortCondition;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\SortCases\SortCaseInterface;

class SortCaseContext
{
    public function __construct(
        protected iterable $sortCaseContext
    ) {
    }

    public function create(FilterSortCondition $sortCondition): ?SortCaseInterface
    {
        /** @var SortCaseInterface $case */
        foreach ($this->sortCaseContext as $case) {
            if ($case->isCanBeProcessed($sortCondition)) {
                return $case;
            }
        }

        return null;
    }
}
