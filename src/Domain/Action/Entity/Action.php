<?php

declare(strict_types=1);

namespace App\Domain\Action\Entity;

use App\Domain\Action\Services\GenerateActionAssociateToUserDomainService;
use App\Domain\Action\ValueObject\ActionTitle;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\ValueObject\Uuid;

class Action
{
    private Uuid $responsible;
    private Uuid $uuid;
    private ActionTitle $title;
    //Only allow creation of Actions from the Domain service that is checking invariants
    public const FRIEND_CLASSES = [GenerateActionAssociateToUserDomainService::class];

    /**
     * @throws
     * Protected due to there is a builder class with an invariant to protect
     * DomainException
     */
    protected function __construct(Uuid $responsible,ActionTitle $title)
    {
        $this->setResponsible($responsible);
        $this->setUuid(Uuid::random());
        $this->setTitle($title);
    }

    /**
     * @return Uuid|null
     */
    public function getResponsible(): ?Uuid
    {
        return $this->responsible;
    }

    /**
     * @param Uuid $userUuid
     */
    private function setResponsible(Uuid $userUuid): void
    {
        $this->responsible = $userUuid;
    }


    /**
     * @return Uuid
     */
    public function getUuid(): Uuid
    {
        return $this->uuid;
    }

    /**
     * @param Uuid $uuid
     */
    private function setUuid(Uuid $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return ActionTitle
     */
    public function getTitle(): ActionTitle
    {
        return $this->title;
    }

    /**
     * @param ActionTitle $title
     */
    public function setTitle(ActionTitle $title): void
    {
        $this->title = $title;
    }
}