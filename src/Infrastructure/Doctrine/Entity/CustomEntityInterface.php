<?php


namespace App\Bundles\InfrastructureBundle\Infrastructure\Doctrine\Entity;

use App\Bundles\InfrastructureBundle\Domain\Entity\DomainEntityInterface;
use App\Bundles\InfrastructureBundle\Infrastructure\Prototype\PrototypeInterface;

interface CustomEntityInterface extends DomainEntityInterface, PrototypeInterface
{
    public static function prefix(): string;
}
