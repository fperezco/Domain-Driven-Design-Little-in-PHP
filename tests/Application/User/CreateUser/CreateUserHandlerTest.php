<?php

declare(strict_types=1);

namespace App\Tests\Application\User\CreateUser;

use App\Application\UseCase\User\CreateUser\CreateUserCommand;
use App\Application\UseCase\User\CreateUser\CreateUserHandler;
use App\Domain\DeliveryCompany\Entity\DeliveryCompany;
use App\Domain\Shared\Event\DomainEvent;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\User\Entity\User;
use App\Domain\User\Entity\UserEmail;
use App\Domain\User\Entity\UserFirstName;
use App\Domain\User\Entity\UserLastName;
use App\Domain\User\Entity\UserUsername;
use App\Domain\User\Event\UserCreatedEvent;
use App\Tests\Application\User\UserTestCase;
use App\Tests\Resources\Factory\FakerFactory;

class CreateUserHandlerTest extends UserTestCase
{
    private $userRepository;
    private $deliveryCompanyRepository;
    private $eventBus;

    public function setUp(): void
    {
        $this->fakerFactory = new FakerFactory();
        $this->userRepository = $this->getUserRepositoryMock();
        $this->deliveryCompanyRepository = $this->getDeliveryCompanyRepositoryMock();
        $this->eventBus = $this->getEventBusMock();
        parent::setUp();
    }

    /** @test */
    public function if_the_delivery_company_that_will_register_user_does_not_exist_exception_is_launched()
    {
        //GIVEN
        $createUserCommandHandler = new CreateUserHandler(
            $this->deliveryCompanyRepository,
            $this->userRepository,
            $this->eventBus
        );
        $createUserCommand = $this->fakerFactory->getValidCreateUserCommand();
        $this->deliveryCompanyRepositoryShouldNotFindDeliveryCompany($createUserCommand->getDeliveryCompanyUuid());

        //THEN
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('deliveryCompany.notfound');

        //WHEN
        $createUserCommandHandler->__invoke($createUserCommand);
    }


    /** @test */
    public function if_all_is_ok_user_is_saved_and_event_is_launched()
    {
        //GIVEN
        $deliveryCompany = $this->fakerFactory->getValidDeliveryCompany();
        $createUserCommandHandler = new CreateUserHandler(
            $this->deliveryCompanyRepository,
            $this->userRepository,
            $this->eventBus
        );
        $createUserCommand = $this->fakerFactory->getValidCreateUserCommand($deliveryCompany->getUuid()->value());
        $this->deliveryCompanyRepositoryShouldFindDeliveryCompany($deliveryCompany);
        $userCreated = $this->getExpectedUserGeneratedByDeliveryCompany($deliveryCompany, $createUserCommand);

        //THEN
        $this->userRepositoryShouldSaveUser($userCreated);
        $this->eventBusShouldLaunchEvent(
            new UserCreatedEvent($deliveryCompany->getUuid()->value(), $userCreated->getUuid()->value())
        );

        //WHEN
        $createUserCommandHandler->__invoke($createUserCommand);
    }


    private function deliveryCompanyRepositoryShouldNotFindDeliveryCompany(
        string $deliveryCompanyUuid
    ) {
        $this->deliveryCompanyRepository->method('findOneById')
            ->with(new Uuid($deliveryCompanyUuid))
            ->willReturn(null);
    }

    private function deliveryCompanyRepositoryShouldFindDeliveryCompany(
        DeliveryCompany $deliveryCompany
    ) {
        $this->deliveryCompanyRepository->method('findOneById')
            ->with($deliveryCompany->getUuid())
            ->willReturn($deliveryCompany);
    }

    /**
     * @param DeliveryCompany $deliveryCompany
     * @param \App\Application\UseCase\User\CreateUser\CreateUserCommand $createUserCommand
     * @return void
     * @throws DomainException
     */
    private function getExpectedUserGeneratedByDeliveryCompany(
        DeliveryCompany $deliveryCompany,
        CreateUserCommand $createUserCommand
    ): User {
        return $deliveryCompany->registerANewUser(
            new Uuid($createUserCommand->getUserUuid()),
            new UserFirstName($createUserCommand->getFirstName()),
            new UserLastName($createUserCommand->getLastName()),
            new UserEmail($createUserCommand->getEmail()),
            new UserUsername($createUserCommand->getUsername())
        );
    }

    /**
     * @param User $userCreated
     * @return void
     */
    private function userRepositoryShouldSaveUser(User $userCreated): void
    {
        $this->userRepository->expects($this->once())->method('save')
            ->with($userCreated);
    }

    /**
     * @param DomainEvent $domainEvent
     * @return void
     */
    private function eventBusShouldLaunchEvent(DomainEvent $domainEvent): void
    {
        $this->eventBus->expects($this->once())->method('publish')->with($domainEvent);
    }

}