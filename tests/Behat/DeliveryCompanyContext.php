<?php

namespace App\Tests\Behat;

use App\Application\UseCase\DeliveryCompany\UpdateAmountOfTODOTasks\DeliveryCompany_TaskCreatedEventHandler;
use App\Domain\DeliveryCompany\Repository\DeliveryCompanyRepositoryInterface;
use App\Domain\DeliveryCompany\ValueObject\DeliveryCompanyName;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\User\Event\TaskCreatedEvent;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Tests\Resources\Factory\FakerFactory;
use Behat\Behat\Context\Context;

class DeliveryCompanyContext implements Context
{
    private DeliveryCompanyRepositoryInterface $deliveryCompanyRepository;
    private FakerFactory $fakerFactory;
    private UserRepositoryInterface $userRepository;

    public function __construct(DeliveryCompanyRepositoryInterface $deliverCompanyRepository, UserRepositoryInterface $userRepository)
    {
        $this->deliveryCompanyRepository = $deliverCompanyRepository;
        $this->fakerFactory = new FakerFactory();
        $this->userRepository = $userRepository;
    }

    /**
     * @Given /^I have a delivery company in the database with uuid:(.*) and name:(.*)$/
     */
    public function iHaveADeliveryCompanyWithName(string $uuid,string $deliverCompanyName): void
    {
        $deliverCompany = $this->fakerFactory->getValidDeliveryCompany(new Uuid($uuid));
        $deliverCompany->setName(new DeliveryCompanyName($deliverCompanyName));
        $this->deliveryCompanyRepository->save($deliverCompany);

    }

    /**
     * @Then /^emulated the async processing of the TaskCreatedEvent for user with uuid:(.*) and task with uuid:(.*)$/
     */
    public function emulatedTheAsyncProcessingOfTheTaskCreatedEventForUserWithUuidAndTaskWithUuid(
        string $userUuid,string $taskUuid
    ) {
        $deliveryCompany_TaskCreatedEventHandler = new DeliveryCompany_TaskCreatedEventHandler(
            $this->deliveryCompanyRepository,
            $this->userRepository,
        );

        $taskCreatedEvent = new TaskCreatedEvent($userUuid,$taskUuid);
        $deliveryCompany_TaskCreatedEventHandler->__invoke($taskCreatedEvent);
    }
}