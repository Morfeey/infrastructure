<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\SortCases;

use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterSortCondition as SortCondition;
use App\Bundles\InfrastructureBundle\Domain\Repository\DomainRepositoryInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\Helper\PrefixCreator;
use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;

abstract class DefaultSortCase implements SortCaseInterface
{
    use PrefixCreator;

    abstract protected function createStringType(SortCondition $sortCondition): string;
    public function process(
        DoctrineQueryBuilder $builder,
        SortCondition $sortCondition,
        DomainRepositoryInterface $repository
    ): DoctrineQueryBuilder {
        return $builder->addOrderBy(
            "{$this->createPrefixByRepository($repository)}.{$sortCondition->getField()->getFieldString()}",
            $this->createStringType($sortCondition)
        );
    }
}
