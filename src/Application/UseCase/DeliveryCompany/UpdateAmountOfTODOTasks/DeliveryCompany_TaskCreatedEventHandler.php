<?php

declare(strict_types=1);

namespace App\Application\UseCase\DeliveryCompany\UpdateAmountOfTODOTasks;

use App\Application\Shared\Event\EventHandlerInterface;
use App\Application\UseCase\DeliveryCompany\Shared\DeliveryCompanyFinder;
use App\Application\UseCase\User\Shared\UserFinder;
use App\Domain\DeliveryCompany\Repository\DeliveryCompanyRepositoryInterface;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\User\Event\TaskCreatedEvent;
use App\Domain\User\Repository\UserRepositoryInterface;

/**
 *  We should decrease on remove task or delete user as well TODO
 */
class DeliveryCompany_TaskCreatedEventHandler implements EventHandlerInterface
{
    private UserFinder $userFinder;
    private DeliveryCompanyFinder $deliveryCompanyFinder;
    private DeliveryCompanyRepositoryInterface $deliveryCompanyRepository;

    public function __construct(DeliveryCompanyRepositoryInterface $deliveryCompanyRepository,UserRepositoryInterface $userRepository)
    {
        $this->userFinder = new UserFinder($userRepository);
        $this->deliveryCompanyFinder = new DeliveryCompanyFinder($deliveryCompanyRepository);
        $this->deliveryCompanyRepository = $deliveryCompanyRepository;
    }

    /**
     * @param TaskCreatedEvent $taskCreatedEvent
     * @return void
     * @throws DomainException
     */
    public function __invoke(TaskCreatedEvent $taskCreatedEvent): void
    {
        $user = $this->userFinder->__invoke(new Uuid($taskCreatedEvent->getUserUuid()));
        $deliveryCompany = $this->deliveryCompanyFinder->__invoke($user->getDeliveryCompanyUuid());
        //in a sharding environment or another BC environment not repository method will be used
        //we could consume other BC information via open-host, rpc, etc..
        //always recalculate to prevent double events or our of order in async events infrastructure
        $tasksInTodo = $this->deliveryCompanyRepository->getAmountOfTodoTasksInsideThisDeliveryCompany($deliveryCompany);
        $deliveryCompany->setNumberOfTaskInTODO($tasksInTodo);
        $this->deliveryCompanyRepository->save($deliveryCompany);
    }
}