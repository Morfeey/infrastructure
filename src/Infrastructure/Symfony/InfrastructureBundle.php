<?php


namespace App\Bundles\InfrastructureBundle\Infrastructure\Symfony;

use App\Bundles\InfrastructureBundle\Infrastructure\Symfony\DependencyInjection\InfrastructureBundleExtension;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class InfrastructureBundle extends Bundle
{
    public function getPath(): string
    {
        return __DIR__ . '/../..';
    }

    /**
     * @return InfrastructureBundleExtension
     */
    #[Pure] public function getContainerExtension(): InfrastructureBundleExtension
    {
        return new InfrastructureBundleExtension();
    }
}
