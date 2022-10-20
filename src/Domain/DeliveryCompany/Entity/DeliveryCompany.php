<?php

declare(strict_types=1);

namespace App\Domain\DeliveryCompany\Entity;

use App\Domain\DeliveryCompany\ValueObject\DeliveryCompanyName;
use App\Domain\DeliveryCompany\ValueObject\DeliveryCompanyVIPLevel;
use App\Domain\Shared\Aggregate\AggregateRoot;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\User\Entity\User;
use App\Domain\User\Entity\UserEmail;
use App\Domain\User\Entity\UserFirstName;
use App\Domain\User\Entity\UserLastName;
use App\Domain\User\Entity\UserUsername;

class DeliveryCompany extends AggregateRoot
{
    private Uuid $uuid;
    private DeliveryCompanyName $name;
    private int $numberOfTaskInTODO;
    private DeliveryCompanyVIPLevel $vipLevel;

    private function __construct(Uuid $uuid, DeliveryCompanyName $name)
    {
        $this->setUuid($uuid);
        $this->setName($name);
        $this->setNumberOfTaskInTODO(0);
        $this->setVipLevel(new DeliveryCompanyVIPLevel(DeliveryCompanyVIPLevel::BASE));
    }

    public static function create(Uuid $uuid, DeliveryCompanyName $name): DeliveryCompany
    {
        $deliveryCompany = new self($uuid, $name);
        //place events here
        return $deliveryCompany;
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
     * @return DeliveryCompanyName
     */
    public function getName(): DeliveryCompanyName
    {
        return $this->name;
    }

    /**
     * @param DeliveryCompanyName $name
     */
    public function setName(DeliveryCompanyName $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getNumberOfTaskInTODO(): int
    {
        return $this->numberOfTaskInTODO;
    }

    /**
     * @param int $numberOfTaskInTODO
     */
    public function setNumberOfTaskInTODO(int $numberOfTaskInTODO): void
    {
        $this->numberOfTaskInTODO = $numberOfTaskInTODO;
    }

    /**
     * @return DeliveryCompanyVIPLevel
     */
    public function getVipLevel(): DeliveryCompanyVIPLevel
    {
        return $this->vipLevel;
    }

    /**
     * @param DeliveryCompanyVIPLevel $vipLevel
     */
    private function setVipLevel(DeliveryCompanyVIPLevel $vipLevel): void
    {
        $this->vipLevel = $vipLevel;
    }

    /**
     * @param Uuid $userUuid
     * @param UserFirstName $firstname
     * @param UserLastName $lastname
     * @param UserEmail $email
     * @param UserUsername $username
     * @return User
     */
    public function registerANewUser(
        Uuid $userUuid,
        UserFirstName $firstname,
        UserLastName $lastname,
        UserEmail $email,
        UserUsername $username
    ): User {
        return User::create($this->getUuid(), $userUuid, $firstname, $lastname, $email, $username);
    }

}