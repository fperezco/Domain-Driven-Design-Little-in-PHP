<?php

declare(strict_types=1);

namespace App\Tests\Application\DeliveryCompany\UpdateAmountOfTODOTasks;

use App\Application\UseCase\DeliveryCompany\UpdateAmountOfTODOTasks\DeliveryCompany_TaskCreatedEventHandler;
use App\Domain\DeliveryCompany\Entity\DeliveryCompany;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\User\Entity\User;
use App\Domain\User\Event\TaskCreatedEvent;
use App\Tests\Application\DeliveryCompany\DeliveryCompanyTestCase;
use App\Tests\Resources\Factory\FakerFactory;

class DeliveryCompany_TaskCreatedEventHandlerTest extends DeliveryCompanyTestCase
{
    private $userRepository;
    private $deliveryCompanyRepository;

    public function setUp(): void
    {
        $this->fakerFactory = new FakerFactory();
        $this->userRepository = $this->getUserRepositoryMock();
        $this->deliveryCompanyRepository = $this->getDeliveryCompanyRepositoryMock();
        parent::setUp();
    }

    /** @test */
    public function number_of_todo_task_inside_users_inside_delivery_company_are_recalculated_after_a_task_is_created()
    {
        //GIVEN
        $numberOfGlobalTaskInTodoInSystem = 4;
        $deliveryCompany = $this->fakerFactory->getValidDeliveryCompany();
        $user1 = $this->fakerFactory->getValidUserInsideThisDeliveryCompany($deliveryCompany);
        $user2 = $this->fakerFactory->getValidUserInsideThisDeliveryCompany($deliveryCompany);
        $this->addXNumberOfTasksToUser($user1, 2);
        $this->addXNumberOfTasksToUser($user2, 2);

        $deliveryCompany_TaskCreatedEventHandler = new DeliveryCompany_TaskCreatedEventHandler(
            $this->deliveryCompanyRepository,
            $this->userRepository,
        );
        $taskCreatedEvent = new TaskCreatedEvent(
            $user2->getUuid()->value(),
            $this->fakerFactory->getValidUUid()->value()
        );
        $this->userRepositoryShouldFindUser($user2);
        $this->deliveryCompanyRepositoryShouldFindDeliveryCompany($deliveryCompany);
        $this->deliveryCompanyRepositoryGetAmountOfTodoTasksInsideThisDeliveryCompanyShouldReturn(
            $numberOfGlobalTaskInTodoInSystem
        );

        //THEN
        $this->assertEquals(0, $deliveryCompany->getNumberOfTaskInTODO());
        $expectedDeliveryCompany = $this->getExpectedDeliveryCompanyWithNumberOfTasks(
            $deliveryCompany,
            $numberOfGlobalTaskInTodoInSystem
        );
        $this->deliveryCompanyRepositoryShouldSaveDeliveryCompany($expectedDeliveryCompany);

        //WHEN
        $deliveryCompany_TaskCreatedEventHandler->__invoke($taskCreatedEvent);

        //THEN
        $this->assertEquals($numberOfGlobalTaskInTodoInSystem, $deliveryCompany->getNumberOfTaskInTODO());
    }

    private function getExpectedDeliveryCompanyWithNumberOfTasks(
        DeliveryCompany $deliveryCompany,
        int $numberOfTasksInTodo
    ): DeliveryCompany {
        $dc2 = clone $deliveryCompany;
        $dc2->setNumberOfTaskInTODO($numberOfTasksInTodo);
        return $dc2;
    }

    private function userRepositoryShouldFindUser(User $user)
    {
        $this->userRepository->method('findOneById')
            ->with($user->getUuid())
            ->willReturn($user);
    }


    /**
     * @param DeliveryCompany $dc
     * @return void
     */
    private function deliveryCompanyRepositoryShouldSaveDeliveryCompany(DeliveryCompany $dc): void
    {
        $this->deliveryCompanyRepository->expects($this->once())->method('save')
            ->with($dc);
    }

    /**
     * @param User $user1
     * @param int $numberOfTasks
     * @return void
     * @throws DomainException
     */
    private function addXNumberOfTasksToUser(User $user1, int $numberOfTasks): void
    {
        for ($i = 0; $i < $numberOfTasks; $i++) {
            $user1->receiveANewTask(
                $this->fakerFactory->getValidUUid(),
                $this->fakerFactory->getValidTaskTitle(),
                $this->fakerFactory->getValidTaskPriority()
            );
        }
    }

    private function deliveryCompanyRepositoryShouldFindDeliveryCompany(DeliveryCompany $deliveryCompany)
    {
        $this->deliveryCompanyRepository->method('findOneById')
            ->with($deliveryCompany->getUuid())
            ->willReturn($deliveryCompany);
    }

    private function deliveryCompanyRepositoryGetAmountOfTodoTasksInsideThisDeliveryCompanyShouldReturn(
        int $numberOfGlobalTaskInTodoInSystem
    ) {
        $this->deliveryCompanyRepository->method('getAmountOfTodoTasksInsideThisDeliveryCompany')
            ->willReturn($numberOfGlobalTaskInTodoInSystem);
    }
}