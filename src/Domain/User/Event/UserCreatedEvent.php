<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\Shared\Event\DomainEvent;

final class UserCreatedEvent extends DomainEvent
{
    private string $deliveryCompanyUuid;

    public function __construct(
        string $deliveryCompanyUuid,
        string $id,
        string $eventId = null,
        string $occurredOn = null
    ) {
        $this->deliveryCompanyUuid = $deliveryCompanyUuid;
        parent::__construct($id, $eventId, $occurredOn);
    }

    public static function eventName(): string
    {
        return 'user.created';
    }

    /**
     * @return string
     */
    public function getDeliveryCompanyUuid(): string
    {
        return $this->deliveryCompanyUuid;
    }
}
