<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\Context;

use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterCondition;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\ConditionCases\ConditionTypeCaseInterface;

class ConditionTypeContext
{
    protected bool $isLinearStarted;
    public function __construct(
        protected readonly iterable $conditionTypeCases
    ) {
        $this->isLinearStarted = false;
    }

    public function create(FilterCondition $condition): ?ConditionTypeCaseInterface
    {
        /** @var ConditionTypeCaseInterface $case */
        foreach ($this->conditionTypeCases as $case) {
            if ($case->isCanBeProcessed($condition)) {
                return $case;
            }
        }

        return null;
    }
}
