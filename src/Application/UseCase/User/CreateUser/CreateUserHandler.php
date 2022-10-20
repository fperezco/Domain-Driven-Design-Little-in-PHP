<?php

declare(strict_types=1);

namespace App\Application\UseCase\User\CreateUser;

use App\Application\Shared\Command\CommandHandlerInterface;
use App\Application\Shared\Event\EventBusInterface;
use App\Application\UseCase\DeliveryCompany\Shared\DeliveryCompanyFinder;
use App\Domain\DeliveryCompany\Repository\DeliveryCompanyRepositoryInterface;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\User\Entity\UserEmail;
use App\Domain\User\Entity\UserFirstName;
use App\Domain\User\Entity\UserLastName;
use App\Domain\User\Entity\UserUsername;
use App\Domain\User\Repository\UserRepositoryInterface;

class CreateUserHandler implements CommandHandlerInterface
{
    private UserRepositoryInterface $userRepository;
    private DeliveryCompanyFinder $deliveryCompanyFinder;
    private EventBusInterface $bus;

    public function __construct(
        DeliveryCompanyRepositoryInterface $deliveryCompanyRepository,
        UserRepositoryInterface $userRepository,
        EventBusInterface $bus
    ) {
        $this->deliveryCompanyFinder = new DeliveryCompanyFinder($deliveryCompanyRepository);
        $this->userRepository = $userRepository;
        $this->bus = $bus;
    }


    /**
     * @param CreateUserCommand $createUserCommand
     * @return void
     * @throws DomainException
     */
    public function __invoke(CreateUserCommand $createUserCommand): void
    {
        $deliveryCompany = $this->deliveryCompanyFinder->__invoke(
            new Uuid($createUserCommand->getDeliveryCompanyUuid())
        );
        $user = $deliveryCompany->registerANewUser(
            new Uuid($createUserCommand->getUserUuid()),
            new UserFirstName($createUserCommand->getFirstName()),
            new UserLastName($createUserCommand->getLastName()),
            new UserEmail($createUserCommand->getEmail()),
            new UserUsername($createUserCommand->getUsername())
        );
        $this->userRepository->save($user);
        $this->bus->publish(...$user->pullDomainEvents());
    }

}