<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency;

use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\Service\QueryBuilderProcessService;

readonly class DefaultDependency implements DefaultDependencyForRepositoryInterface
{
    public function __construct(
        private QueryBuilderProcessService $queryBuilderProcessService
    ) {
    }

    public function getQueryBuilderProcessService(): QueryBuilderProcessService
    {
        return $this->queryBuilderProcessService;
    }
}
