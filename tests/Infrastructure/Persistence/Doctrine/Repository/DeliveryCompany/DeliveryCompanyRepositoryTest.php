<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Persistence\Doctrine\Repository\DeliveryCompany;

use App\Domain\DeliveryCompany\Entity\DeliveryCompany;
use App\Domain\DeliveryCompany\Repository\DeliveryCompanyRepositoryInterface;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Tests\Infrastructure\FunctionalTestCase;

class DeliveryCompanyRepositoryTest extends FunctionalTestCase
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
    public function it_should_save_a_DeliveryCompany(): void
    {
        //GIVEN
        $deliveryCompany = $this->fakerFactory->getValidDeliveryCompany();
        $this->deliveryCompanyRepository->save($deliveryCompany);
        $this->clearUnitOfWork();
        //WHEN
        $bdDeliveryCompany = $this->deliveryCompanyRepository->findOneById($deliveryCompany->getUuid());
        //THEN
        $this->assertDeliveryCompaniesEquals($deliveryCompany, $bdDeliveryCompany);
    }


    /** @test */
    public function it_should_find_an_existing_DeliveryCompany_by_Uuid(): void
    {
        //GIVEN
        $deliveryCompany = $this->fakerFactory->getValidDeliveryCompany();
        $this->deliveryCompanyRepository->save($deliveryCompany);
        $this->clearUnitOfWork();
        //WHEN
        $bdDeliveryCompany = $this->deliveryCompanyRepository->findOneById($deliveryCompany->getUuid());
        //THEN
        $this->assertDeliveryCompaniesEquals($deliveryCompany, $bdDeliveryCompany);
    }

    /** @test */
    public function it_should_find_an_existing_DeliveryCompany_by_criteria_eg_DeliveryCompany_name(): void
    {
        //GIVEN
        $deliveryCompany = $this->fakerFactory->getValidDeliveryCompany();
        $this->deliveryCompanyRepository->save($deliveryCompany);
        $this->clearUnitOfWork();
        //WHEN
        $bdDeliveryCompany = $this->deliveryCompanyRepository->findOneByCriteria(
            ['name.value' => $deliveryCompany->getName()->value()]
        );
        //THEN
        $this->assertDeliveryCompaniesEquals($deliveryCompany, $bdDeliveryCompany);
    }


    /** @test */
    public function it_should_delete_an_existing_DeliveryCompany(): void
    {
        //GIVEN
        $deliveryCompany = $this->fakerFactory->getValidDeliveryCompany();
        $this->deliveryCompanyRepository->save($deliveryCompany);
        $this->clearUnitOfWork();
        //WHEN
        $bdDeliveryCompany = $this->deliveryCompanyRepository->findOneById($deliveryCompany->getUuid());
        $this->deliveryCompanyRepository->delete($bdDeliveryCompany);
        //THEN
        $this->assertEquals(null, $this->deliveryCompanyRepository->findOneById($deliveryCompany->getUuid()));
    }

    /** @test */
    public function it_should_retrieve_all_DeliveryCompanies(): void
    {
        //GIVEN
        $deliveryCompany = $this->fakerFactory->getValidDeliveryCompany();
        $deliveryCompany2 = $this->fakerFactory->getValidDeliveryCompany();
        $this->deliveryCompanyRepository->save($deliveryCompany);
        $this->deliveryCompanyRepository->save($deliveryCompany2);
        $this->clearUnitOfWork();
        //WHEN
        $deliveryCompanies = $this->deliveryCompanyRepository->findAll();
        //THEN
        $this->assertEquals(2, count($deliveryCompanies));
    }

    /** @test */
    public function it_should_return_all_tasks_in_todo_inside_users_inside_a_delivery_Company(): void
    {
        //GIVEN
        $deliveryCompany = $this->fakerFactory->getValidDeliveryCompany();
        $deliveryCompany2 = $this->fakerFactory->getValidDeliveryCompany();
        $this->deliveryCompanyRepository->save($deliveryCompany);
        $this->deliveryCompanyRepository->save($deliveryCompany2);
        $user1 = $this->fakerFactory->getValidUserInsideThisDeliveryCompany($deliveryCompany);
        $user2 = $this->fakerFactory->getValidUserInsideThisDeliveryCompany($deliveryCompany);
        $user3 = $this->fakerFactory->getValidUserInsideThisDeliveryCompany($deliveryCompany2);
        $this->addXNumberOfTasksToUser($user1,2);
        $this->addXNumberOfTasksToUser($user2,3);
        $this->addXNumberOfTasksToUser($user3,3);
        $this->userRepository->save($user1);
        $this->userRepository->save($user2);
        $this->userRepository->save($user3);
        $this->clearUnitOfWork();
        //WHEN
        $numberOfTasksTodoForDeliveryCompany1 = $this->deliveryCompanyRepository->getAmountOfTodoTasksInsideThisDeliveryCompany($deliveryCompany);
        //THEN
        $this->assertEquals(5, $numberOfTasksTodoForDeliveryCompany1);
    }


    private function assertDeliveryCompaniesEquals(
        DeliveryCompany $deliveryCompany,
        DeliveryCompany $bdDeliveryCompany
    ): void {
        $this->assertEquals($deliveryCompany->getUuid(), $bdDeliveryCompany->getUuid());
        $this->assertEquals($deliveryCompany->getName(), $bdDeliveryCompany->getName());
        $this->assertEquals($deliveryCompany->getVipLevel(), $bdDeliveryCompany->getVipLevel());
        $this->assertEquals($deliveryCompany->getNumberOfTaskInTODO(), $bdDeliveryCompany->getNumberOfTaskInTODO());
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


}