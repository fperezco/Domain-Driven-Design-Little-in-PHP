<?php

declare(strict_types=1);

namespace App\Tests\Resources\Factory\FactoryTraits;

use App\Domain\DeliveryCompany\Entity\DeliveryCompany;
use App\Domain\DeliveryCompany\ValueObject\DeliveryCompanyName;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\User\Entity\User;

trait DeliveryCompanyFactoryTrait
{
    /**
     * @throws DomainException
     */
    public function getValidDeliveryCompanyName(): DeliveryCompanyName
    {
        return new DeliveryCompanyName($this->faker->regexify('[a-z]{10}'));
    }

    public function getValidDeliveryCompany(Uuid $uuid = null): DeliveryCompany
    {
        $deliveryCompanyUuid = $uuid ?: $this->getValidUUid();
        return DeliveryCompany::create($deliveryCompanyUuid, $this->getValidDeliveryCompanyName());
    }

    public function getValidUserInsideThisDeliveryCompany(DeliveryCompany $dc): User
    {
        return $dc->registerANewUser(
            Uuid::random(),
            $this->getValidUserFirstName(),
            $this->getValidUserLastName(),
            $this->getValidUserEmail(),
            $this->getValidUserUsername()
        );
    }

    /**
     * @throws DomainException
     */
    public function getValidUser(): User
    {
        return User::create(
            Uuid::random(),
            Uuid::random(),
            $this->getValidUserFirstName(),
            $this->getValidUserLastName(),
            $this->getValidUserEmail(),
            $this->getValidUserUsername()
        );
    }

}