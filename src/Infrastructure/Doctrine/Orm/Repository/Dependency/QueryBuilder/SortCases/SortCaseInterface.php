<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\SortCases;

use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterSortCondition as SortCondition;
use App\Bundles\InfrastructureBundle\Domain\Repository\DomainRepositoryInterface;
use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;

interface SortCaseInterface
{
    public function isCanBeProcessed(SortCondition $sortCondition): bool;
    public function process(
        DoctrineQueryBuilder $builder,
        SortCondition $sortCondition,
        DomainRepositoryInterface $repository
    ): DoctrineQueryBuilder;
}
