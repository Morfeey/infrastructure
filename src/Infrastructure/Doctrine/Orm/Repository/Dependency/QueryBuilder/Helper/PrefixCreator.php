<?php
declare(strict_types=1);

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository\Dependency\QueryBuilder\Helper;

use App\Bundles\InfrastructureBundle\Domain\Repository\DomainRepositoryInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Entity\CustomEntityInterface;

trait PrefixCreator
{
    public function createPrefixByRepository(DomainRepositoryInterface $repository): string
    {
        /** @var CustomEntityInterface $entity */
        $entity = $repository->create();

        return $entity->prefix();
    }
}
