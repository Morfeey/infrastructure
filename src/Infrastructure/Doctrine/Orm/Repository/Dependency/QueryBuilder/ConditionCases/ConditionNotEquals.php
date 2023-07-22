<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\ConditionCases;

use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\ConditionTypeEnum;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterCondition as Condition;
use App\Bundles\InfrastructureBundle\Domain\Repository\DomainRepositoryInterface;
use Doctrine\ORM\Query\Expr\Comparison as DoctrineComparison;
use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;

class ConditionNotEquals extends DefaultConditionType implements ConditionTypeCaseInterface
{
    public function isCanBeProcessed(Condition $condition): bool
    {
        return $condition->getType() === ConditionTypeEnum::NOT_EQUALS;
    }

    public function createExpression(DoctrineQueryBuilder $builder, Condition $condition, DomainRepositoryInterface $repository): mixed
    {
        return $builder->expr()->neq(
            $this->createFieldKey($condition, $repository),
            $this->createFieldPreparedValue($condition, $repository)
        );
    }
}
