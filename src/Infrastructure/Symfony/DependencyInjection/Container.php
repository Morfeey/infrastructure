<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Symfony\DependencyInjection;

use App\Kernel;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Container
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $kernel)
    {
        $this->container = $kernel;
    }

    public function get(string $className): ?object
    {
        return $this->container->get($className);
    }
}
