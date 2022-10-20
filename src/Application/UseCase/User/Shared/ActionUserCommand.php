<?php
declare(strict_types=1);

namespace App\Application\UseCase\User\Shared;

use App\Application\Shared\Command\CommandInterface;

abstract class ActionUserCommand implements CommandInterface
{

    private string $deliveryCompanyUuid;
    private string $userUuid;
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $username;

    public function __construct(string $deliveryCompanyUuid,string $userUuid, string $firstName, string $lastName, string $email, string $username)
    {
        $this->deliveryCompanyUuid = $deliveryCompanyUuid;
        $this->userUuid = $userUuid;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->email = $email;
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getDeliveryCompanyUuid(): string
    {
        return $this->deliveryCompanyUuid;
    }

    /**
     * @return string
     */
    public function getUserUuid(): string
    {
        return $this->userUuid;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

}