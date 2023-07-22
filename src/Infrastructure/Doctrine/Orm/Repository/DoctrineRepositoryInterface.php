<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Orm\Repository;

use App\Bundles\InfrastructureBundle\Domain\Repository\DomainRepositoryInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Entity\CustomEntityInterface;
use Doctrine\Persistence\ObjectRepository;

/**
 * @method CustomEntityInterface create()
 */
interface DoctrineRepositoryInterface extends DomainRepositoryInterface, ObjectRepository
{
    public function getTableName(): string;
}
