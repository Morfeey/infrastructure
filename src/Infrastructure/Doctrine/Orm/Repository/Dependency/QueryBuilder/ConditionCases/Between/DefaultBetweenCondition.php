<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\ConditionCases\Between;

use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterCondition as Condition;
use App\Bundles\InfrastructureBundle\Domain\Repository\DomainRepositoryInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\ConditionCases\ConditionTypeCaseInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\ConditionCases\DefaultConditionType;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\Exception\ConditionBuilderException;
use App\Bundles\InfrastructureBundle\Infrastructure\Helper\ArrayCollection\CollectionInterface;

abstract class DefaultBetweenCondition extends DefaultConditionType implements ConditionTypeCaseInterface
{
    private const SECOND_PARAMETER_POSTFIX = '_between_and';

    public function createParameters(Condition $condition, DomainRepositoryInterface $repository): CollectionInterface
    {
        $firstParameterValue = $condition->getValue()[0] ?? null;
        $secondParameterValue = $condition->getValue()[1] ?? null;
        if ($firstParameterValue === null || $secondParameterValue === null) {
            throw new ConditionBuilderException($condition, 'One or more parameters between is null');
        }

        return
            $this->createCollectionPrototype()
                ->add($this->createConditionParameter($firstParameterValue, $this->createKey($condition, $repository)))
                ->add($this->createConditionParameter($secondParameterValue, $this->createSecondKey($condition, $repository)));
    }

    private function createSecondKey(Condition $condition, DomainRepositoryInterface $repository): string
    {
        return parent::createKey($condition, $repository) . self::SECOND_PARAMETER_POSTFIX;
    }

    public function createSecondFieldPreparedValue(Condition $condition, DomainRepositoryInterface $repository): string
    {
        return parent::createFieldPreparedValue($condition, $repository) . self::SECOND_PARAMETER_POSTFIX;
    }
}
