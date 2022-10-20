<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Application\Shared\Query\QueryBusInterface;
use App\Application\Shared\Query\QueryInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerQueryBus implements QueryBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function dispatch(QueryInterface $query)
    {
        return $this->handle($query);
    }
}
