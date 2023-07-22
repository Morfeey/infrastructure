<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\WhereCases;

use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterCondition as Condition;
use Doctrine\ORM\Query\Expr\Comparison as DoctrineComparison;
use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;

interface WhereCaseInterface
{
    public function isCanBeProcessed(Condition $condition): bool;
    public function process(DoctrineQueryBuilder $builder, mixed $expression): DoctrineQueryBuilder;
}
