<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\ConditionCases;

use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\ConditionTypeEnum;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterCondition as Condition;
use App\Bundles\InfrastructureBundle\Domain\Repository\DomainRepositoryInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\Exception\ConditionBuilderException;
use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;

class ConditionTypeIn extends DefaultConditionType implements ConditionTypeCaseInterface
{

    public function isCanBeProcessed(Condition $condition): bool
    {
        return $condition->getType() === ConditionTypeEnum::IN;
    }

    public function createExpression(DoctrineQueryBuilder $builder, Condition $condition, DomainRepositoryInterface $repository): mixed
    {
        if (!is_array($condition->getValue())) {
            throw new ConditionBuilderException($condition, 'The value cannot be of the type array.');
        }

        return $builder->expr()->in(
            $this->createFieldKey($condition, $repository),
            $this->createFieldPreparedValue($condition, $repository)
        );
    }
}
