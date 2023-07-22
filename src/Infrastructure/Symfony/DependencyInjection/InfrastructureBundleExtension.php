<?php


namespace App\Bundles\InfrastructureBundle\Infrastructure\Symfony\DependencyInjection;

class InfrastructureBundleExtension extends DefaultBundleExtension
{
    public function getCurrentDir(): string
    {
        return __DIR__;
    }
}
