<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\Context;

use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterCondition as Condition;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\WhereCases\WhereCaseInterface;

class WhereContext
{
    public function __construct(
        protected iterable $whereCases
    ) {
    }

    public function create(Condition $condition): ?WhereCaseInterface
    {
        /** @var WhereCaseInterface $case */
        foreach ($this->whereCases as $case) {
            if ($case->isCanBeProcessed($condition)) {
                return $case;
            }
        }

        return null;
    }
}
