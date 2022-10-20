<?php

declare(strict_types=1);

namespace App\Tests\Resources\Factory\FactoryTraits;

use App\Application\UseCase\User\AssignTaskToUser\AssignTaskToUserCommand;
use App\Application\UseCase\User\CreateUser\CreateUserCommand;
use App\Application\UseCase\User\DeleteUser\DeleteUserCommand;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\User\Entity\UserEmail;
use App\Domain\User\Entity\UserFirstName;
use App\Domain\User\Entity\UserLastName;
use App\Domain\User\Entity\UserUsername;

trait UserFactoryTrait
{
    /**
     * @throws DomainException
     */
    public function getValidUserUsername(): UserUsername
    {
        return new UserUsername($this->faker->regexify('[a-z]{10}'));
    }

    public function getValidPlainPassword(): string
    {
        return $this->faker->regexify('[a-z]{8}[A-Z]{5}[1-9]{2}^^');
    }


    /**
     * @throws DomainException
     */
    public function getValidUserFirstName(): UserFirstName
    {
        return new UserFirstName($this->faker->regexify('[a-z]{10}'));
    }

    /**
     * @throws DomainException
     */
    public function getValidUserLastName(): UserLastName
    {
        return new UserLastName($this->faker->regexify('[a-z]{10}'));
    }

    /**
     * @throws DomainException
     */
    public function getValidUserEmail(): UserEmail
    {
        return new UserEmail($this->faker->email());
    }


    public function getValidCreateUserCommand(
        string $deliveryCompany = null,
        string $uuid = null,
        string $firstNameValue = null,
        string $lastNameValue = null,
        string $email = null,
        string $username = null
    ): CreateUserCommand {
        $deliveryCompanyUuid = $deliveryCompany ?: $this->getValidUUid()->value();
        $userUuid = $uuid ?: $this->getValidUUid()->value();
        $firstName = $firstNameValue ?: $this->getValidUserFirstName()->value();
        $lastName = $lastNameValue ?: $this->getValidUserLastName()->value();
        $username = $username ?: $this->getValidUserUsername()->value();
        $email = $email ?: $this->getValidUserEmail()->value();

        return new CreateUserCommand(
            $deliveryCompanyUuid,
            $userUuid,
            $firstName,
            $lastName,
            $email,
            $username
        );
    }

    public function getValidAssignTaskToUserCommand(
        string $userUuid = null,
        string $taskTitle = null,
        string $taskPriority = null
    ): AssignTaskToUserCommand {
        $userUuid = $userUuid ?: $this->getValidUUid()->value();
        $taskTitle = $taskTitle ?: $this->getValidTaskTitle()->value();
        $taskPriority = $taskPriority ?: $this->getValidTaskPriority()->value();

        return new AssignTaskToUserCommand(
            $userUuid,
            $taskTitle,
            $taskPriority
        );
    }


    public function getValidDeleteUserCommand(string $uuid = null): DeleteUserCommand
    {
        $uuid = $uuid ?: $this->getValidUUid()->value();
        return new DeleteUserCommand($uuid);
    }


}