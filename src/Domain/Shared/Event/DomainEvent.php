<?php

declare(strict_types=1);

namespace App\Domain\Shared\Event;

use App\Domain\Shared\Utils;
use DateTimeImmutable;

abstract class DomainEvent
{
    private string $entityId;
    private string $eventId;
    private string $occurredOn;

    public function __construct(string $entityId, string $eventId = null, string $occurredOn = null)
    {
        $this->entityId = $entityId;
        $this->eventId    = $eventId ?: strval(time());
        $this->occurredOn = $occurredOn ?: (new DateTimeImmutable())->format('Y-m-d H:i');
    }

    abstract public static function eventName(): string;

    public function getEntityId(): string
    {
        return $this->entityId;
    }

    public function getEventId(): string
    {
        return $this->eventId;
    }

    public function getOccurredOn(): string
    {
        return $this->occurredOn;
    }
}
