<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\DeleteUser;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Event\EventBusInterface;
use App\Application\UseCase\User\Shared\UserFinder;
use App\Domain\Shared\DateTime\DateTimeProvider;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\User\Repository\UserRepositoryInterface;

class DeleteUserHandler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userRepository;
    private EventBusInterface $bus;
    private UserFinder $userFinder;
    private DateTimeProvider $dateTimeProvider;

    public function __construct(
        UserRepositoryInterface $userRepository,
        DateTimeProvider $dateTimeProvider,
        EventBusInterface $bus
    ) {
        $this->userRepository = $userRepository;
        $this->bus = $bus;
        $this->userFinder = new UserFinder($userRepository);
        $this->dateTimeProvider = $dateTimeProvider;
    }

    /**
     * @param DeleteUserCommand $deleteUserCommand
     * @return void
     * @throws DomainException
     */
    public function __invoke(DeleteUserCommand $deleteUserCommand): void
    {
        $userToDelete = $this->userFinder->__invoke(new Uuid($deleteUserCommand->getUuid()));
        $userToDelete->removeFromSystem($this->dateTimeProvider);
        $this->userRepository->save($userToDelete);
        $this->bus->publish(...$userToDelete->pullDomainEvents());
    }

}