<?php

declare(strict_types=1);

namespace App\Tests\Domain\User\Entity;


use App\Domain\Shared\DateTime\DateTimeProvider;
use App\Domain\Shared\Exception\DomainException;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\User\Entity\User;
use App\Tests\Application\TestCaseWithFactory;


class UserTest extends TestCaseWithFactory
{

    /** @test */
    public function no_more_than_3_task_can_be_assigned_to_user()
    {
        //GIVEN
        $user = $this->fakerFactory->getValidUser();
        $this->assignMaxNumberOfAllowedTasksToUser($user);

        //THEN
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('max.number.of.tasks.reached');

        //WHEN
        $user->receiveANewTask(
            Uuid::random(),
            $this->fakerFactory->getValidTaskTitle(),
            $this->fakerFactory->getValidTaskPriority()
        );
    }

    /** @test */
    public function when_a_user_is_deleted_the_current_date_will_be_stored(): void
    {
        //GIVEN
        $user = $this->fakerFactory->getValidUser();
        $date = new \DateTime('2022-01-01 00:00:00');
        $dateTimeProvider = $this->createMock(DateTimeProvider::class);
        $dateTimeProvider->method('getCurrentDate')->willReturn($date);

        //WHEN
        $user->removeFromSystem($dateTimeProvider);

        //THEN
        $this->assertEquals($user->getDeletionDate(),$date);
    }

    /**
     * @param User $user
     * @return void
     * @throws DomainException
     */
    private function assignMaxNumberOfAllowedTasksToUser(User $user): void
    {
        for ($i = 0; $i < User::MAX_TASKS; $i++) {
            $user->receiveANewTask(
                Uuid::random(),
                $this->fakerFactory->getValidTaskTitle(),
                $this->fakerFactory->getValidTaskPriority()
            );
        }
    }

}