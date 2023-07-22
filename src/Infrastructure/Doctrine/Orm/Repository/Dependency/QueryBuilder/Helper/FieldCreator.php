<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\Helper;

use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterCondition as Condition;
use App\Bundles\InfrastructureBundle\Domain\Repository\DomainRepositoryInterface;

trait FieldCreator
{
    use PrefixCreator;

    protected function createFieldKey(Condition $condition, DomainRepositoryInterface $repository): string
    {
        return "{$this->createPrefixByRepository($repository)}.{$condition->getField()->getFieldString()}";
    }

    protected function createFieldPreparedValue(Condition $condition, DomainRepositoryInterface $repository): string
    {
        return ":{$this->createPrefixByRepository($repository)}_{$condition->getField()->getFieldString()}";
    }

    public function createKey(Condition $condition, DomainRepositoryInterface $repository): string
    {
        return "{$this->createPrefixByRepository($repository)}_{$condition->getField()->getFieldString()}";
    }
}
