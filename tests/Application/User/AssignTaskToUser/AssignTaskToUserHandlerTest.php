<?php

declare(strict_types=1);

namespace App\Tests\Application\User\AssignTaskToUser;

use App\Application\UseCase\User\AssignTaskToUser\AssignTaskToUserCommand;
use App\Application\UseCase\User\AssignTaskToUser\AssignTaskToUserHandler;
use App\Domain\Shared\Event\DomainEvent;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\Task\ValueObject\TaskPriority;
use App\Domain\Task\ValueObject\TaskTitle;
use App\Domain\User\Entity\User;
use App\Domain\User\Event\TaskCreatedEvent;
use App\Tests\Application\User\UserTestCase;
use App\Tests\Resources\Factory\FakerFactory;

class AssignTaskToUserHandlerTest extends UserTestCase
{
    private $userRepository;
    private $eventBus;

    public function setUp(): void
    {
        $this->fakerFactory = new FakerFactory();
        $this->userRepository = $this->getUserRepositoryMock();
        $this->eventBus = $this->getEventBusMock();
        parent::setUp();
    }

    /** @test */
    public function if_the_user_that_will_contains_the_new_task_does_not_exist_exception_is_launched()
    {
        //GIVEN
        $assignTaskToUserHandler = new AssignTaskToUserHandler(
            $this->userRepository,
            $this->eventBus
        );
        $assignTaskToUserCommand = $this->fakerFactory->getValidAssignTaskToUserCommand();
        $this->userRepositoryShouldNotFindUser($assignTaskToUserCommand->getUserUuid());

        //THEN
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('user.notfound');

        //WHEN
        $assignTaskToUserHandler->__invoke($assignTaskToUserCommand);
    }


    /** @test */
    public function if_all_is_ok_task_is_saved_and_event_is_launched()
    {
        //GIVEN
        $deliveryCompany = $this->fakerFactory->getValidDeliveryCompany();
        $user = $this->fakerFactory->getValidUserInsideThisDeliveryCompany($deliveryCompany);
        $user->pullDomainEvents(); //pull previous events
        $assignTaskToUserHandler = new AssignTaskToUserHandler(
            $this->userRepository,
            $this->eventBus
        );
        $assignTaskToUserCommand = $this->fakerFactory->getValidAssignTaskToUserCommand($user->getUuid()->value());
        $this->userRepositoryShouldFindUser($user);
        $userWithTasksExpected = $this->getExpectedUserWithTask($user, $assignTaskToUserCommand);

        //THEN
        $this->userRepository->expects($this->once())->method('save');
        $this->eventBus->expects($this->once())->method('publish')->with($this->isInstanceOf(TaskCreatedEvent::class));


        //WHEN
        $assignTaskToUserHandler->__invoke($assignTaskToUserCommand);
    }


    private function userRepositoryShouldNotFindUser(
        string $userUuid
    ) {
        $this->userRepository->method('findOneById')
            ->with(new Uuid($userUuid))
            ->willReturn(null);
    }

    private function userRepositoryShouldFindUser(
        User $user
    ) {
        $this->userRepository->method('findOneById')
            ->with($user->getUuid())
            ->willReturn($user);
    }

    /**
     * @param User $user
     * @param AssignTaskToUserCommand $assignTaskToUserCommand
     * @return void
     * @throws DomainException
     */
    private function getExpectedUserWithTask(
        User $user,
        AssignTaskToUserCommand $assignTaskToUserCommand
    ): User {
        $user2 = clone $user;
        $user2->receiveANewTask(
            Uuid::random(),
            new TaskTitle($assignTaskToUserCommand->getTaskTitle()),
            new TaskPriority($assignTaskToUserCommand->getTaskPriority())
        );
        return $user2;
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