<?php
declare(strict_types=1);

namespace App\Tests\Application\User\GetUsers;

use App\Application\UseCase\User\GetUsers\GetUsersHandler;
use App\Application\UseCase\User\GetUsers\GetUsersQuery;
use App\Tests\Application\User\UserTestCase;
use App\Tests\Resources\Factory\FakerFactory;

class GetUsersHandlerTest extends UserTestCase
{
    private $userRepository;
    private $userResponseConverter;

    public function setUp(): void
    {
        $this->fakerFactory = new FakerFactory();
        $this->userRepository = $this->getUserRepositoryMock();
        $this->userResponseConverter = $this->getUserResponseConverterMock();
        parent::setUp();
    }

    /** @test */
    public function result_from_repository_will_be_pass_to_mapper()
    {
        //GIVEN
        $deliveryCompany1 = $this->fakerFactory->getValidDeliveryCompany();
        $user1 = $this->fakerFactory->getValidUserInsideThisDeliveryCompany($deliveryCompany1);
        $user2 = $this->fakerFactory->getValidUserInsideThisDeliveryCompany($deliveryCompany1);
        $usersInSystem = [$user1,$user2];
        $this->userRepositoryShouldReturnUsers($usersInSystem);
        $getUsersHandler = new GetUsersHandler($this->userRepository, $this->userResponseConverter);

        //THEN
        $this->userResponseConverter->expects($this->once())->method('map')->with($usersInSystem);

        //WHEN
        $getUsersHandler->__invoke(new GetUsersQuery());

    }

    private function userRepositoryShouldReturnUsers(array $usersInSystem)
    {
        $this->userRepository->method('findAll')
            ->willReturn($usersInSystem);
    }

}
