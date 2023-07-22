<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency;

use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\Service\QueryBuilderProcessService;

interface DefaultDependencyForRepositoryInterface
{
    public function getQueryBuilderProcessService(): QueryBuilderProcessService;
}
