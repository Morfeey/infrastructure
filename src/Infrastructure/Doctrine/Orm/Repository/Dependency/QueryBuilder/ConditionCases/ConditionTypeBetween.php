<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\ConditionCases;

use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\ConditionTypeEnum;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterCondition as Condition;
use App\Bundles\InfrastructureBundle\Domain\Repository\DomainRepositoryInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\ConditionCases\Between\DefaultBetweenCondition;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\Exception\ConditionBuilderException;
use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;

class ConditionTypeBetween extends DefaultBetweenCondition implements ConditionTypeCaseInterface
{
    public function isCanBeProcessed(Condition $condition): bool
    {
        return $condition->getType() === ConditionTypeEnum::BETWEEN;
    }

    public function createExpression(DoctrineQueryBuilder $builder, Condition $condition, DomainRepositoryInterface $repository): mixed
    {
        if (!is_array($condition->getValue()) || count($condition->getValue()) < 2) {
            throw new ConditionBuilderException($condition, "Count parameter < 2");
        }

        return $builder->expr()->between(
            $this->createFieldKey($condition, $repository),
            $this->createFieldPreparedValue($condition, $repository),
            $this->createSecondFieldPreparedValue($condition, $repository)
        );
    }
}
