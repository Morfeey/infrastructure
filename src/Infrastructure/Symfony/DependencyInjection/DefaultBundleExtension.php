<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Symfony\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

abstract class DefaultBundleExtension extends Extension
{
    public const PATH = '/../Resources/config';
    public const RESOURCE = 'services.yaml';
    public const ANNOTATIONS = 'annotations.yaml';

    /**
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $fileLoader = new YamlFileLoader(
            $container,
            new FileLocator(static::getCurrentDir() . static::PATH)
        );

        $fileLoader->load(static::RESOURCE);
        foreach ($this->afterServicesLoadConfigs() as $configName) {
            $fileLoader->load($configName);
        }

//        if (file_exists(static::getCurrentDir() . static::PATH . DIRECTORY_SEPARATOR . static::ANNOTATIONS)) {
//            //            $fileLoader->import(static::ANNOTATIONS);
//        }
    }

    public function afterServicesLoadConfigs(): array
    {
        return [];
    }

    abstract public function getCurrentDir(): string;
}
