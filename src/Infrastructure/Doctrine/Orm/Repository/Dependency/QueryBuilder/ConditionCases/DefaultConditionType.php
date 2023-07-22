<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\ConditionCases;

use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterCondition as Condition;
use App\Bundles\InfrastructureBundle\Domain\Repository\DomainRepositoryInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Entity\CustomEntityInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\ConditionCases\Dto\ConditionParameterDto;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\Helper\FieldCreator;
use App\Bundles\InfrastructureBundle\Infrastructure\Helper\ArrayCollection\Collection;
use App\Bundles\InfrastructureBundle\Infrastructure\Helper\ArrayCollection\CollectionInterface;

abstract class DefaultConditionType implements ConditionTypeCaseInterface
{
    use FieldCreator;
    protected CollectionInterface $collection;

    protected function createCollectionPrototype(): CollectionInterface
    {
        $this->collection = $this->collection ?? new Collection();

        return clone $this->collection;
    }

    protected function createConditionParameter(mixed $value, string $key): ConditionParameterDto
    {
        return new ConditionParameterDto($value, $key);
    }

    public function createParameters(Condition $condition, DomainRepositoryInterface $repository): CollectionInterface
    {
        return
            $this->createCollectionPrototype()
                ->add(
                    $this->createConditionParameter(
                        $condition->getValue(),
                        $this->createKey($condition, $repository)
                    )
                );
    }
}
