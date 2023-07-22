<?php

namespace App\Bundles\InfrastructureBundle\Application\Contract\Bus;

use App\Bundles\InfrastructureBundle\Application\Contract\Command\CommandInterface;

interface CommandBusInterface
{
    public function execute(CommandInterface $command): mixed;
}
