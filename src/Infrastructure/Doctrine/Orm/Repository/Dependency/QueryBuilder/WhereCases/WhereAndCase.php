<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\WhereCases;

use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\ConditionWhereType;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterCondition as Condition;
use Doctrine\ORM\Query\Expr\Comparison as DoctrineComparison;
use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;

class WhereAndCase implements WhereCaseInterface
{
    public function isCanBeProcessed(Condition $condition): bool
    {
        return $condition->getWhereType() === ConditionWhereType::AND;
    }

    public function process(DoctrineQueryBuilder $builder, mixed $expression): DoctrineQueryBuilder
    {
        return $builder->andWhere($expression);
    }
}
