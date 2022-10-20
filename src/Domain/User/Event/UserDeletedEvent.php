<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\Shared\Event\DomainEvent;

final class UserDeletedEvent extends DomainEvent
{
    public static function eventName(): string
    {
        return 'user.deleted';
    }
}
