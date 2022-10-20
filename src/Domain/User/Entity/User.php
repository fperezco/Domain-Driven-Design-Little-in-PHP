<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Shared\Aggregate\AggregateRoot;
use App\Domain\Shared\DateTime\DateTimeProvider;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\Task\Entity\Task;
use App\Domain\User\Event\UserCreatedEvent;
use App\Domain\User\Event\UserDeletedEvent;
use App\Domain\User\Traits\UserTaskTrait;

class User extends AggregateRoot
{
    use UserTaskTrait;

    public const MAX_TASKS = 3;
    private Uuid $deliveryCompany;
    private Uuid $uuid;
    private UserFirstName $firstname;
    private UserLastName $lastname;
    private UserUsername $username;
    private UserEmail $email;
    /** @var iterable | Task[] */
    private iterable $tasks;
    private int $version;
    private ?\DateTime $deletionDate;

    private function __construct(
        Uuid $deliveryCompany,
        Uuid $uuid,
        UserFirstName $firstname,
        UserLastName $lastname,
        UserEmail $email,
        UserUsername $username
    ) {
        $this->setDeliveryCompanyUuid($deliveryCompany);
        $this->setUuid($uuid);
        $this->setFirstName($firstname);
        $this->setLastName($lastname);
        $this->setEmail($email);
        $this->setUsername($username);
        $this->setDeletionDate();
        $this->tasks = [];
        $this->version = 1;
    }

    /**
     * @param Uuid $deliveryCompany
     * @param Uuid $userUuid
     * @param UserFirstName $firstname
     * @param UserLastName $lastname
     * @param UserEmail $email
     * @param UserUsername $username
     * @return static
     */
    public static function create(
        Uuid $deliveryCompany,
        Uuid $userUuid,
        UserFirstName $firstname,
        UserLastName $lastname,
        UserEmail $email,
        UserUsername $username
    ): self {
        $user = new self($deliveryCompany, $userUuid, $firstname, $lastname, $email, $username);
        $user->recordEvent(new UserCreatedEvent($deliveryCompany->value(), $userUuid->value()));
        return $user;
    }

    public function removeFromSystem(DateTimeProvider $dateTimeProvider): void
    {
        $this->recordEvent(new UserDeletedEvent($this->getUuid()->value()));
        $this->setDeletionDate($dateTimeProvider->getCurrentDate());
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @return Uuid
     */
    public function getDeliveryCompanyUuid(): Uuid
    {
        return $this->deliveryCompany;
    }

    /**
     * @param Uuid $deliveryCompany
     */
    private function setDeliveryCompanyUuid(Uuid $deliveryCompany): void
    {
        $this->deliveryCompany = $deliveryCompany;
    }

    /**
     * @param Uuid $uuid
     */
    private function setUuid(Uuid $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @return Uuid
     */
    public function getUuid(): Uuid
    {
        return $this->uuid;
    }


    public function getFirstname(): UserFirstName
    {
        return $this->firstname;
    }

    private function setFirstname(UserFirstName $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return UserUsername
     */
    public function getUsername(): UserUsername
    {
        return $this->username;
    }

    /**
     * @param UserUsername $username
     */
    private function setUsername(UserUsername $username): void
    {
        $this->username = $username;
    }


    /**
     * @return UserLastName
     */
    public function getLastName(): UserLastName
    {
        return $this->lastname;
    }

    /**
     * @param UserLastName $lastname
     */
    private function setLastname(UserLastName $lastname): void
    {
        $this->lastname = $lastname;
    }

    /**
     * @return UserEmail
     */
    public function getEmail(): UserEmail
    {
        return $this->email;
    }

    /**
     * @param UserEmail $email
     */
    private function setEmail(UserEmail $email): void
    {
        $this->email = $email;
    }

    /**
     * @return \DateTime|null
     */
    public function getDeletionDate(): ?\DateTime
    {
        return $this->deletionDate;
    }

    /**
     * @param \DateTime|null $deletionDate
     */
    private function setDeletionDate(?\DateTime $deletionDate = null): void
    {
        $this->deletionDate = $deletionDate;
    }

    public function updateFirstName(UserFirstName $firstName): void
    {
        $this->setFirstname($firstName);
    }

}