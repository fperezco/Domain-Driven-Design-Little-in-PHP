<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\AssignTaskToUser;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Event\EventBusInterface;
use App\Application\UseCase\DeliveryCompany\Shared\DeliveryCompanyFinder;
use App\Application\UseCase\User\Shared\UserFinder;
use App\Domain\DeliveryCompany\Repository\DeliveryCompanyRepositoryInterface;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\Task\ValueObject\TaskPriority;
use App\Domain\Task\ValueObject\TaskTitle;
use App\Domain\User\Entity\UserEmail;
use App\Domain\User\Entity\UserFirstName;
use App\Domain\User\Entity\UserLastName;
use App\Domain\User\Entity\UserUsername;
use App\Domain\User\Repository\UserRepositoryInterface;

class AssignTaskToUserHandler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userRepository;
    private EventBusInterface $bus;
    private UserFinder $userFinder;

    public function __construct(
        UserRepositoryInterface $userRepository,
        EventBusInterface $bus
    ) {
        $this->userFinder = new UserFinder($userRepository);
        $this->userRepository = $userRepository;
        $this->bus = $bus;
    }


    /**
     * @param AssignTaskToUserCommand $assignTaskToUserCommand
     * @return void
     * @throws DomainException
     */
    public function __invoke(AssignTaskToUserCommand $assignTaskToUserCommand): void
    {
        $user = $this->userFinder->__invoke(
            new Uuid($assignTaskToUserCommand->getUserUuid())
        );
        $user->receiveANewTask(
            Uuid::random(), //not provided due to later we are going to use in the concurrency tests
            new TaskTitle($assignTaskToUserCommand->getTaskTitle()),
            new TaskPriority($assignTaskToUserCommand->getTaskPriority())
        );
        $this->userRepository->save($user);
        $this->bus->publish(...$user->pullDomainEvents());
    }

}