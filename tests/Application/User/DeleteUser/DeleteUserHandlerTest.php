<?php
declare(strict_types=1);

namespace App\Tests\Application\User\DeleteUser;

use App\Application\UseCase\User\DeleteUser\DeleteUserHandler;
use App\Domain\Shared\DateTime\DateTimeProvider;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\User\Entity\User;
use App\Domain\User\Event\UserDeletedEvent;
use App\Tests\Application\User\UserTestCase;
use App\Tests\Resources\Factory\FakerFactory;

class DeleteUserHandlerTest extends UserTestCase
{

    private $userRepository;
    private $eventBus;
    private $dateTimeProvider;

    public function setUp(): void
    {
        $this->fakerFactory = new FakerFactory();
        $this->userRepository = $this->getUserRepositoryMock();
        $this->eventBus = $this->getEventBusMock();
        $this->dateTimeProvider = $this->getDateTimeProviderMock();
        parent::setUp();
    }


    /** @test */
    public function if_the_user_that_you_want_to_delete_not_exist_exception_is_launched()
    {
        //GIVEN
        $userToDelete = $this->getSampleUser();
        $deleteUserCommand = $this->fakerFactory->getValidDeleteUserCommand( $userToDelete->getUuid()->value());
        $this->userRepositoryShouldNotFindUser($userToDelete);
        $deleteUserCommandHandler = new DeleteUserHandler($this->userRepository, $this->dateTimeProvider,$this->eventBus);

        //THEN
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('user.notfound');

        //WHEN
        $deleteUserCommandHandler->__invoke($deleteUserCommand);
    }

    /** @test */
    public function after_being_deleted_used_is_saved_and_UserDeletedEvent_is_launched()
    {
        //GIVEN
        $userToDelete = $this->getSampleUser();
        $userToDelete->pullDomainEvents(); //pull creation event
        $deleteUserCommand = $this->fakerFactory->getValidDeleteUserCommand($userToDelete->getUuid()->value());

        $this->userRepositoryShouldFindUser($userToDelete);
        $deleteUserCommandHandler = new DeleteUserHandler($this->userRepository, new DateTimeProvider(),$this->eventBus);

        //THEN
        $this->eventBusShouldPublishUserDeletedEvent($userToDelete);
        $this->userRepositoryShouldSaveUser($userToDelete);

        //WHEN
        $deleteUserCommandHandler->__invoke($deleteUserCommand);
        $this->assertNotNull($userToDelete->getDeletionDate());
    }


    private function userRepositoryShouldNotFindUser(
        User $user
    ) {
        $this->userRepository->method('findOneById')
            ->with($user->getUuid())
            ->willReturn(null);
    }

    private function userRepositoryShouldFindUser(User $user)
    {
        $this->userRepository->method('findOneById')
            ->with($user->getUuid())
            ->willReturn($user);
    }

    /**
     * @param User $userToDelete
     * @return void
     */
    private function eventBusShouldPublishUserDeletedEvent(User $userToDelete): void
    {
        $this->eventBus->expects($this->once())->method('publish')
            ->with(new UserDeletedEvent($userToDelete->getUuid()->value()));
    }

    /**
     * @param User $userToDelete
     * @return void
     */
    private function userRepositoryShouldSaveUser(User $userToDelete): void
    {
        $this->userRepository->expects($this->once())->method('save')
            ->with($userToDelete);
    }

    /**
     * @return User
     */
    private function getSampleUser(): User
    {
        $deliveryCompany = $this->fakerFactory->getValidDeliveryCompany();
        $userToDelete = $this->fakerFactory->getValidUserInsideThisDeliveryCompany($deliveryCompany);
        return $userToDelete;
    }
}    