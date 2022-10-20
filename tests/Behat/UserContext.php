<?php

namespace App\Tests\Behat;

use App\Application\UseCase\DeliveryCompany\Shared\DeliveryCompanyFinder;
use App\Application\UseCase\DeliveryCompany\UpdateAmountOfTODOTasks\DeliveryCompany_TaskCreatedEventHandler;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\User\Entity\UserEmail;
use App\Domain\User\Entity\UserFirstName;
use App\Domain\User\Entity\UserLastName;
use App\Domain\User\Entity\UserUsername;
use App\Domain\User\Event\TaskCreatedEvent;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Tests\Resources\Factory\FakerFactory;
use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

class UserContext implements Context
{

    private UserRepositoryInterface $userRepository;
    private FakerFactory $fakerFactory;
    private DeliveryCompanyFinder $deliveryCompanyFinder;

    public function __construct(DeliveryCompanyFinder $deliveryCompanyFinder, UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->fakerFactory = new FakerFactory();
        $this->deliveryCompanyFinder = $deliveryCompanyFinder;
    }


    /**
     * @Given /^I have saved user in the database with uuid:(.*) ,firstName:(.*),lastName:(.*),userName:(.*) ,email:(.*) and that belongs to the delivery company with uuid:(.*)$/
     */
    public function iHaveSavedUserInTheDatabaseWithParameters(
        string $uuid,
        string $firstName,
        string $lastName,
        string $userName,
        string $email,
        string $deliveryCompanyUuid
    ) {
        $deliveryCompany = $this->deliveryCompanyFinder->__invoke(new Uuid($deliveryCompanyUuid));
        $user = $deliveryCompany->registerANewUser(new Uuid($uuid),new UserFirstName($firstName),new UserLastName($lastName),new UserEmail($email),new UserUsername($userName));
        $this->userRepository->save($user);
    }

}