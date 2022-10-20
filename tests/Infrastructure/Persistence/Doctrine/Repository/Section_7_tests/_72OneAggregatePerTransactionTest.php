<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Persistence\Doctrine\Repository\Section_7_tests;

use App\Domain\DeliveryCompany\Entity\DeliveryCompany;
use App\Domain\DeliveryCompany\Repository\DeliveryCompanyRepositoryInterface;
use App\Domain\DeliveryCompany\ValueObject\DeliveryCompanyName;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\User\Entity\User;
use App\Domain\User\Entity\UserFirstName;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Tests\Infrastructure\FunctionalTestCase;

class _72OneAggregatePerTransactionTest extends FunctionalTestCase
{
    private deliveryCompanyRepositoryInterface $deliveryCompanyRepository;
    private UserRepositoryInterface $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->deliveryCompanyRepository = self::getContainer()->get(DeliveryCompanyRepositoryInterface::class);
        $this->userRepository = self::getContainer()->get(UserRepositoryInterface::class);
        $this->truncateEntities([DeliveryCompany::class,User::class]);
    }


    /** @test */
    public function _721_IMPLICIT_entity_is_not_save_until_you_call_flush_see_with_debugger_and_sql_log(): void
    {
        //GIVEN
        $deliveryCompany = $this->fakerFactory->getValidDeliveryCompany();
        $this->deliveryCompanyRepository->save($deliveryCompany);
        $this->clearUnitOfWork();

        //WHEN
        $dbDeliveryCompany1 = $this->deliveryCompanyRepository->findOneById($deliveryCompany->getUuid());
        $dbDeliveryCompany1->setName(new DeliveryCompanyName("delivery company 1 updated name"));
        $this->deliveryCompanyRepository->save($dbDeliveryCompany1);
        $this->clearUnitOfWork();

        //THEN
        $dbDeliveryCompany1x = $this->deliveryCompanyRepository->findOneById($deliveryCompany->getUuid());
        $this->assertEquals("delivery company 1 updated name", $dbDeliveryCompany1x->getName()->value());
    }

    /** @test */
    public function _722_IMPLICIT_you_dont_need_to_call_persist_to_save_a_pre_managed_entity(): void
    {
        //GIVEN
        $deliveryCompany = $this->fakerFactory->getValidDeliveryCompany();
        $this->deliveryCompanyRepository->save($deliveryCompany);
        $this->clearUnitOfWork();

        //WHEN
        $dbDeliveryCompany1 = $this->deliveryCompanyRepository->findOneById($deliveryCompany->getUuid());
        $dbDeliveryCompany1->setName(new DeliveryCompanyName("delivery company 1 updated name"));
        $this->deliveryCompanyRepository->update($dbDeliveryCompany1);
        $this->clearUnitOfWork();

        //THEN
        $dbDeliveryCompany1x = $this->deliveryCompanyRepository->findOneById($deliveryCompany->getUuid());
        $this->assertEquals("delivery company 1 updated name", $dbDeliveryCompany1x->getName()->value());
    }


    /** @test */
    public function _723_IMPLICIT_other_entities_are_updated_even_if_there_is_not_a_explicit_call_to_persist_due_to_flush(): void
    {
        //GIVEN
        $deliveryCompany = $this->fakerFactory->getValidDeliveryCompany();
        $deliveryCompany2 = $this->fakerFactory->getValidDeliveryCompany();
        $this->deliveryCompanyRepository->save($deliveryCompany);
        $this->deliveryCompanyRepository->save($deliveryCompany2);
        $userIn2 = $this->fakerFactory->getValidUserInsideThisDeliveryCompany($deliveryCompany2);
        $this->userRepository->save($userIn2);
        $this->clearUnitOfWork();

        //WHEN
        $dbDeliveryCompany1 = $this->deliveryCompanyRepository->findOneById($deliveryCompany->getUuid());
        $dbUserIn2 = $this->userRepository->findOneById($userIn2->getUuid());

        $dbDeliveryCompany1->setName(new DeliveryCompanyName("delivery company 1 updated name"));
        $dbUserIn2->updateFirstName(new UserFirstName("new name"));

        $this->deliveryCompanyRepository->save($dbDeliveryCompany1);
        $this->clearUnitOfWork();

        //THEN
        $dbDeliveryCompany1x = $this->deliveryCompanyRepository->findOneById($deliveryCompany->getUuid());
        $dbUserIn2x = $this->userRepository->findOneById($userIn2->getUuid());
        $this->assertEquals("delivery company 1 updated name", $dbDeliveryCompany1x->getName()->value());
        //!!!!!
        $this->assertEquals("new name", $dbUserIn2x->getFirstname()->value());
    }


    /** @test */
    public function _721_EXPLICIT_other_entities_are_not_updated_due_the_concrete_persist_has_not_been_called(): void
    {
        //GIVEN
        $this->markTestSkipped('Will work on change-tracking-policy="DEFERRED_EXPLICIT"');
        $deliveryCompany = $this->fakerFactory->getValidDeliveryCompany();
        $deliveryCompany2 = $this->fakerFactory->getValidDeliveryCompany();
        $this->deliveryCompanyRepository->save($deliveryCompany);
        $this->deliveryCompanyRepository->save($deliveryCompany2);
        $userIn2 = $this->fakerFactory->getValidUserInsideThisDeliveryCompany($deliveryCompany2);
        $this->userRepository->save($userIn2);
        $this->clearUnitOfWork();

        //WHEN
        $dbDeliveryCompany1 = $this->deliveryCompanyRepository->findOneById($deliveryCompany->getUuid());
        $dbUserIn2 = $this->userRepository->findOneById($userIn2->getUuid());

        $dbDeliveryCompany1->setName(new DeliveryCompanyName("delivery company 1 updated name"));
        $dbUserIn2->updateFirstName(new UserFirstName("new name"));

        $this->deliveryCompanyRepository->save($dbDeliveryCompany1);
        $this->clearUnitOfWork();

        //THEN
        $dbDeliveryCompany1x = $this->deliveryCompanyRepository->findOneById($deliveryCompany->getUuid());
        $dbUserIn2x = $this->userRepository->findOneById($userIn2->getUuid());
        $this->assertEquals("delivery company 1 updated name", $dbDeliveryCompany1x->getName()->value());
        //!!!!!
        $this->assertNotEquals("new name", $dbUserIn2x->getFirstname()->value());
    }

    /** @test */
    public function _722_EXPLICIT_you_should_call_persist_to_save_a_pre_managed_entity(): void
    {
        //GIVEN
        $this->markTestSkipped('Will work on change-tracking-policy="DEFERRED_EXPLICIT"');
        $deliveryCompany = $this->fakerFactory->getValidDeliveryCompany();
        $this->deliveryCompanyRepository->save($deliveryCompany);
        $this->clearUnitOfWork();

        //WHEN
        $dbDeliveryCompany1 = $this->deliveryCompanyRepository->findOneById($deliveryCompany->getUuid());
        $dbDeliveryCompany1->setName(new DeliveryCompanyName("delivery company 1 updated name"));
        //In the previous version update/flush was enough
        //$this->deliveryCompanyRepository->update($dbDeliveryCompany1);
        $this->deliveryCompanyRepository->save($dbDeliveryCompany1);
        $this->clearUnitOfWork();

        //THEN
        $dbDeliveryCompany1x = $this->deliveryCompanyRepository->findOneById($deliveryCompany->getUuid());
        $this->assertEquals("delivery company 1 updated name", $dbDeliveryCompany1x->getName()->value());
    }

}