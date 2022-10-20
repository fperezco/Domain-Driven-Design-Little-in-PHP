<?php

declare(strict_types=1);

namespace App\Application\Shared\Mapper\User;


final class UserResponse
{
    public string $deliveryCompanyUuid;
    public string $uuid;
    public string $firstname;
    public string $lastname;
    public string $username;
    public string $email;

    public function __construct(
        string $deliveryCompanyUuid,
        string $uuid,
        string $firstname,
        string $lastname,
        string $username,
        string $email
    ) {
        $this->deliveryCompanyUuid = $deliveryCompanyUuid;
        $this->uuid = $uuid;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->username = $username;
    }
}
