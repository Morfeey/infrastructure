<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Helper\Path;

interface WayInterface
{
    public function subLastSlash();
    public function addLastSlash();
}
