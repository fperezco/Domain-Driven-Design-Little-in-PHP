<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\Shared\Event\DomainEvent;

final class TaskDeletedEvent extends DomainEvent
{
    private string $userUuid;

    public function __construct(
        string $userUuid,
        string $id,
        string $eventId = null,
        string $occurredOn = null
    ) {
        $this->userUuid = $userUuid;
        parent::__construct($id, $eventId, $occurredOn);
    }

    public static function eventName(): string
    {
        return 'task.deleted';
    }

    /**
     * @return string
     */
    public function getUserUuid(): string
    {
        return $this->userUuid;
    }
}
