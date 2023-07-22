<?php

namespace App\Bundles\InfrastructureBundle\Infrastructure\Bus;

use App\Bundles\InfrastructureBundle\Application\Contract\Bus\QueryBusInterface;
use App\Bundles\InfrastructureBundle\Application\Contract\Query\QueryInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class QueryBus implements QueryBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function execute(QueryInterface $query): mixed
    {
        return $this->handle($query);
    }
}
