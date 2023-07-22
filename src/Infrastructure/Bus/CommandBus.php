<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Bus;

use App\Bundles\InfrastructureBundle\Application\Contract\Bus\CommandBusInterface;
use App\Bundles\InfrastructureBundle\Application\Contract\Command\CommandInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class CommandBus implements CommandBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    public function execute(CommandInterface $command): mixed
    {
        return $this->handle($command);
    }
}
