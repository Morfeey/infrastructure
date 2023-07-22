<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\ConditionCases;

use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterCondition as Condition;
use App\Bundles\InfrastructureBundle\Domain\Repository\DomainRepositoryInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\ConditionCases\Dto\ConditionParameterDto;
use App\Bundles\InfrastructureBundle\Infrastructure\Helper\ArrayCollection\CollectionInterface;
use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;

interface ConditionTypeCaseInterface
{
    public function isCanBeProcessed(Condition $condition): bool;

    public function createExpression(
        DoctrineQueryBuilder $builder,
        Condition $condition,
        DomainRepositoryInterface $repository
    ): mixed;

    /**
     * @return CollectionInterface<ConditionParameterDto>|ConditionParameterDto[]
     */
    public function createParameters(Condition $condition, DomainRepositoryInterface $repository): CollectionInterface;
}
