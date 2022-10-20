<?php

declare(strict_types=1);

namespace App\Infrastructure\Bus;

use App\Application\Shared\Event\EventBusInterface;
use App\Domain\Shared\Event\DomainEvent;
use Symfony\Component\Messenger\MessageBusInterface;

class MessengerEventBus implements EventBusInterface
{
    private MessageBusInterface $eventBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            $this->eventBus->dispatch($event);
        }
    }

    public function dispatch($event): void
    {
            $this->eventBus->dispatch($event);
    }
}
