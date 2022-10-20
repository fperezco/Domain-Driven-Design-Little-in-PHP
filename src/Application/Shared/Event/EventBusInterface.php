<?php

declare(strict_types=1);

namespace App\Application\Shared\Event;

use App\Domain\Shared\Event\DomainEvent;

interface EventBusInterface
{
    public function publish(DomainEvent ...$events): void;
}
