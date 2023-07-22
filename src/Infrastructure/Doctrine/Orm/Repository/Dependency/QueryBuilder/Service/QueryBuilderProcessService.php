<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\Service;

use App\Bundles\InfrastructureBundle\Application\Contract\Filter\DefaultFilterInterface;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\Enum\ConditionTypeEnum;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterCondition as Condition;
use App\Bundles\InfrastructureBundle\Application\Filter\Condition\FilterSortCondition;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\ConditionCases\Dto\ConditionParameterDto;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\Context\ConditionTypeContext;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\Context\SortCaseContext;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\Context\WhereContext;
use App\Bundles\InfrastructureBundle\Infrastructure\Helper\ArrayCollection\Collection;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\QueryBuilder as DoctrineQueryBuilder;

readonly class QueryBuilderProcessService
{
    public function __construct(
        private ConditionTypeContext $conditionTypeContext,
        private SortCaseContext $sortCaseContext,
        private WhereContext $whereContext
    ) {
    }

    public function processByFilter(DoctrineQueryBuilder $builder, DefaultFilterInterface $filter): DoctrineQueryBuilder
    {
        if ($filter->isEmpty()) {
            return $builder;
        }

        $this->process($builder, $filter);
        foreach ($filter->getLinearFilterCollection() as $linearFilter) {
            $this->process($builder, $linearFilter, true);
        }

        return $builder;
    }

    private function process(
        DoctrineQueryBuilder $builder,
        DefaultFilterInterface $filter,
        bool $isLinearFilter = false
    ): DoctrineQueryBuilder {

        $filterRepository = $filter->createRepository();
        $conditionParameters = new Collection();
        /** @var Condition $condition */
        foreach ($filter->getConditionCollection() as $condition) {
            $conditionTypeCase = $this->conditionTypeContext->create($condition);
            $whereCase = $this->whereContext->create($condition);
            if (!$conditionTypeCase || !$whereCase) {
                continue;
            }

            $whereCase->process($builder, $conditionTypeCase->createExpression($builder, $condition, $filterRepository));
            $conditionParameters->mergeWithoutReplacement(
                $conditionTypeCase->createParameters($condition, $filterRepository)
            );
        }

        /** @var ConditionParameterDto $conditionParameter */
        foreach ($conditionParameters as $conditionParameter) {
            $builder->setParameter(
                $conditionParameter->getKey(),
                $conditionParameter->getValue(),
                $this->getType($conditionParameter->getValue())
            );
        }

        if (!$isLinearFilter && $filter->getLimit() !== null) {
            $builder->setMaxResults($filter->getLimit());
        }

        if ($filter->isDistinct()) {
            $builder->distinct(true);
        }

        if (!$isLinearFilter && $filter->getSortConditionCollection()->isEmpty()) {
            //            return $builder->addOrderBy(null);
            return $builder;
        }

        /** @var FilterSortCondition $sortCondition */
        foreach ($filter->getSortConditionCollection() as $sortCondition) {
            $sortCase = $this->sortCaseContext->create($sortCondition);
            if (!$sortCase) {
                continue;
            }

            $sortCase->process($builder, $sortCondition, $filterRepository);
        }

        return $builder;
    }

    private function getType(mixed $condition): ?int
    {
        $type = null;
        if (is_string($condition)) {
            $type = ParameterType::STRING;
        }

        if (is_int($condition)) {
            $type = ParameterType::INTEGER;
        }

        if (is_bool($condition)) {
            $type = ParameterType::BOOLEAN;
        }

        return $type;
    }
}
