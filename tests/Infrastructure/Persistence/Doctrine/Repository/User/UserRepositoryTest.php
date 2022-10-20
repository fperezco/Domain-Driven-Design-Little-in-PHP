<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Persistence\Doctrine\Repository\User;

use App\Domain\DeliveryCompany\Entity\DeliveryCompany;
use App\Domain\Shared\DateTime\DateTimeProvider;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\Task\Entity\Task;
use App\Domain\Task\ValueObject\TaskPriority;
use App\Domain\Task\ValueObject\TaskTitle;
use App\Domain\User\Entity\User;
use App\Domain\User\Repository\UserRepositoryInterface;
use App\Tests\Infrastructure\FunctionalTestCase;
use App\Tests\Infrastructure\Persistence\Doctrine\Repository\Share\DeliveryCompanyUtilTrait;

class UserRepositoryTest extends FunctionalTestCase
{
    use DeliveryCompanyUtilTrait;

    private UserRepositoryInterface $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = self::getContainer()->get(UserRepositoryInterface::class);
        $this->truncateEntities([User::class, DeliveryCompany::class]);
        $this->loadDeliveryCompanyFixtures();
    }


    /** @test */
    public function it_should_save_a_user(): void
    {
        //GIVEN
        $user = $this->fakerFactory->getValidUserInsideThisDeliveryCompany($this->deliveryCompany1);
        $this->userRepository->save($user);
        $this->clearUnitOfWork();

        //WHEN
        $bdUser = $this->userRepository->findOneById($user->getUuid());

        //THEN
        $this->assertUsersEquals($user, $bdUser);
    }


    /** @test */
    public function it_should_find_an_existing_user_by_Uuid(): void
    {
        $user = $this->fakerFactory->getValidUserInsideThisDeliveryCompany($this->deliveryCompany1);
        $this->userRepository->save($user);
        $this->clearUnitOfWork();
        $bdUser = $this->userRepository->findOneById($user->getUuid());
        $this->assertUsersEquals($user, $bdUser);
    }

    /** @test */
    public function it_should_find_an_existing_user_by_criteria_eg_username(): void
    {
        $user = $this->fakerFactory->getValidUserInsideThisDeliveryCompany($this->deliveryCompany1);
        $this->userRepository->save($user);
        $this->clearUnitOfWork();
        $bdUser = $this->userRepository->findOneByCriteria(['username.value' => $user->getUserName()->value()]);
        $this->assertUsersEquals($user, $bdUser);
    }


    /** @test */
    public function it_should_delete_an_existing_user(): void
    {
        $user = $this->fakerFactory->getValidUserInsideThisDeliveryCompany($this->deliveryCompany1);
        $this->userRepository->save($user);
        $this->clearUnitOfWork();
        $bdUser = $this->userRepository->findOneById($user->getUuid());
        $this->userRepository->delete($bdUser);

        $this->assertEquals(null, $this->userRepository->findOneById($user->getUuid()));
    }

    /** @test */
    public function it_should_retrieve_all_users(): void
    {
        $user = $this->fakerFactory->getValidUserInsideThisDeliveryCompany($this->deliveryCompany1);
        $user2 = $this->fakerFactory->getValidUserInsideThisDeliveryCompany($this->deliveryCompany1);
        $this->userRepository->save($user);
        $this->userRepository->save($user2);
        $this->clearUnitOfWork();
        $users = $this->userRepository->findAll();
        $this->assertEquals(2, count($users));
    }

    /** @test */
    public function it_should_retrieve_all_users_not_including_the_deleted_ones(): void
    {
        $user = $this->fakerFactory->getValidUserInsideThisDeliveryCompany($this->deliveryCompany1);
        $user2 = $this->fakerFactory->getValidUserInsideThisDeliveryCompany($this->deliveryCompany1);
        $user3 = $this->fakerFactory->getValidUserInsideThisDeliveryCompany($this->deliveryCompany1);
        $this->userRepository->save($user);
        $this->userRepository->save($user2);
        $user3->removeFromSystem(new DateTimeProvider());
        $this->userRepository->save($user3);
        $this->clearUnitOfWork();
        $users = $this->userRepository->findAll();
        $this->assertEquals(2, count($users));
    }

    /** @test */
    public function it_should_save_tasks_for_a_user(): void
    {
        //GIVEN
        $user = $this->fakerFactory->getValidUserInsideThisDeliveryCompany($this->deliveryCompany1);
        $task1Uuid = Uuid::random();
        $task1Title = $this->fakerFactory->getValidTaskTitle();
        $task1Priority = $this->fakerFactory->getValidTaskPriority();
        $user->receiveANewTask($task1Uuid, $task1Title, $task1Priority);
        $this->userRepository->save($user);
        $this->clearUnitOfWork();

        //WHEN
        $bdUser = $this->userRepository->findOneById($user->getUuid());

        //THEN
        $this->assertCount(1,$bdUser->getTasks());
        $this->assertTaskEquals($bdUser->getTasks()[0],$task1Uuid,$task1Title,$task1Priority,$bdUser);
    }


    /** @test */
//    public function user_control_version_test(): void
//    {
//        $user = $this->getSampleUser();
//        $this->userRepository->save($user);
//        $this->clearUnitOfWork();
//        $bdUser = $this->userRepository->findOneById($user->getUuid());
//        $this->assertEquals(1,$bdUser->getVersion());
//        $bdUser->setFirstname(new UserFirstName("asdfasfsadfasdfdsa"));
//        $this->userRepository->save($bdUser);
//        $this->clearUnitOfWork();
//        $bdUser = $this->userRepository->findOneById($user->getUuid());
//        $this->assertEquals(2,$bdUser->getVersion());
//    }


    private function assertUsersEquals(User $user, User $bdUser): void
    {
        $this->assertEquals($user->getUuid(), $bdUser->getUuid());
        $this->assertEquals($user->getFirstname(), $bdUser->getFirstName());
        $this->assertEquals($user->getLastName(), $bdUser->getLastName());
        $this->assertEquals($user->getUsername(), $bdUser->getUsername());
        $this->assertEquals($user->getEmail(), $bdUser->getEmail());
    }

    private function assertTaskEquals(
        Task  $task,
        Uuid $task1Uuid,
        TaskTitle $task1Title,
        TaskPriority $task1Priority,
        User $owner
    ) {
        $this->assertEquals($task->getUuid(), $task1Uuid);
        $this->assertEquals($task->getTitle(),$task1Title);
        $this->assertEquals($task->getPriority(),$task1Priority);
        $this->assertEquals($task->getResponsible()->getUuid(),$owner->getUuid());
    }


}