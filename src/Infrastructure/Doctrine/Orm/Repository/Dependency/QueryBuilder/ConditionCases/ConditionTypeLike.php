<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\ConditionCases;

use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\ConditionTypeEnum;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterCondition as Condition;
use App\Bundles\InfrastructureBundle\Domain\Repository\DomainRepositoryInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\Exception\ConditionBuilderException;
use App\Bundles\InfrastructureBundle\Infrastructure\Helper\ArrayCollection\CollectionInterface;
use Doctrine\ORM\Query\Expr\Comparison as DoctrineComparison;
use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;

class ConditionTypeLike extends DefaultConditionType implements ConditionTypeCaseInterface
{
    public function isCanBeProcessed(Condition $condition): bool
    {
        return $condition->getType() === ConditionTypeEnum::LIKE;
    }

    public function createExpression(DoctrineQueryBuilder $builder, Condition $condition, DomainRepositoryInterface $repository): mixed
    {
        $value = $condition->getValue();
        if (is_array($value) || is_object($value)) {
            throw new ConditionBuilderException($condition, 'The value cannot be type array or object');
        }

        return $builder->expr()->like(
            $this->createFieldKey($condition, $repository),
            $builder->expr()->literal("%{$value}%")
        );
    }

    public function createParameters(Condition $condition, DomainRepositoryInterface $repository): CollectionInterface
    {
        return $this->createCollectionPrototype();
    }
}
